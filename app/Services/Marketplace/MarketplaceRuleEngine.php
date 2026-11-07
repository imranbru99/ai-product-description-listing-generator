<?php

namespace App\Services\Marketplace;

use App\Models\MarketplaceRule;

class MarketplaceRuleEngine
{
    /**
     * Default rules per marketplace if not yet defined in database.
     */
    protected static array $defaultLimits = [
        'amazon' => [
            'title_max' => 200,
            'title_min' => 20,
            'bullet_max_count' => 5,
            'bullet_max_length' => 500,
            'desc_max' => 2000,
            'forbidden_words' => ['#1', 'best seller', 'free shipping', 'money back guarantee', 'cure', 'cheapest', 'fda approved'],
        ],
        'ebay' => [
            'title_max' => 80,
            'title_min' => 15,
            'bullet_max_count' => 5,
            'bullet_max_length' => 400,
            'desc_max' => 4000,
            'forbidden_words' => ['free shipping', 'paypal only', 'best ever'],
        ],
        'walmart' => [
            'title_max' => 100,
            'title_min' => 20,
            'bullet_max_count' => 10,
            'bullet_max_length' => 350,
            'desc_max' => 4000,
            'forbidden_words' => ['amazon', 'ebay', 'cheapest', 'top rated'],
        ],
        'shopify' => [
            'title_max' => 255,
            'title_min' => 10,
            'bullet_max_count' => 8,
            'bullet_max_length' => 500,
            'desc_max' => 10000,
            'forbidden_words' => [],
        ],
        'etsy' => [
            'title_max' => 140,
            'title_min' => 15,
            'bullet_max_count' => 5,
            'bullet_max_length' => 300,
            'desc_max' => 5000,
            'forbidden_words' => ['mass produced', 'wholesale factory'],
        ],
        'generic' => [
            'title_max' => 250,
            'title_min' => 15,
            'bullet_max_count' => 5,
            'bullet_max_length' => 500,
            'desc_max' => 5000,
            'forbidden_words' => ['miracle', 'guaranteed cure'],
        ],
    ];

    /**
     * Audit listing against marketplace rules.
     */
    public static function validate(string $marketplace, string $title, array $bullets = [], ?string $description = null): array
    {
        $code = strtolower($marketplace);
        $rules = static::$defaultLimits[$code] ?? static::$defaultLimits['generic'];

        $dbRule = MarketplaceRule::where('marketplace_code', $code)->first();
        if ($dbRule) {
            $rules['title_max'] = $dbRule->title_max_length;
            $rules['bullet_max_count'] = $dbRule->bullet_max_count;
            $rules['bullet_max_length'] = $dbRule->bullet_max_length;
            $rules['desc_max'] = $dbRule->desc_max_length;
            if (!empty($dbRule->forbidden_terms)) {
                $rules['forbidden_words'] = array_merge($rules['forbidden_words'], $dbRule->forbidden_terms);
            }
        }

        $flags = [];
        $titleLen = mb_strlen($title);

        // Title checks
        if ($titleLen > $rules['title_max']) {
            $flags[] = [
                'type' => 'error',
                'field' => 'title',
                'message' => "Title exceeds {$marketplace} limit of {$rules['title_max']} chars (current: {$titleLen}).",
            ];
        } elseif ($titleLen < $rules['title_min']) {
            $flags[] = [
                'type' => 'warning',
                'field' => 'title',
                'message' => "Title is shorter than recommended minimum of {$rules['title_min']} chars.",
            ];
        }

        // Bullets count check
        $bulletCount = count($bullets);
        if ($bulletCount > $rules['bullet_max_count']) {
            $flags[] = [
                'type' => 'error',
                'field' => 'bullet_points',
                'message' => "Bullet count ({$bulletCount}) exceeds maximum allowed ({$rules['bullet_max_count']}).",
            ];
        }

        // Forbidden words check
        $contentToCheck = strtolower($title . ' ' . implode(' ', $bullets) . ' ' . ($description ?? ''));
        foreach ($rules['forbidden_words'] as $banned) {
            if (str_contains($contentToCheck, strtolower($banned))) {
                $flags[] = [
                    'type' => 'error',
                    'field' => 'compliance',
                    'message' => "Forbidden term detected for {$marketplace}: '{$banned}'.",
                ];
            }
        }

        $hasErrors = collect($flags)->contains('type', 'error');
        $hasWarnings = collect($flags)->contains('type', 'warning');

        $status = $hasErrors ? 'failed' : ($hasWarnings ? 'warning' : 'passed');
        $score = max(50, 100 - (count($flags) * 15));

        return [
            'status' => $status,
            'score' => $score,
            'flags' => $flags,
            'rules_applied' => $rules,
        ];
    }
}
