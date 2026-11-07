<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'marketplace_code',
        'marketplace_name',
        'title_max_length',
        'bullet_max_count',
        'bullet_max_length',
        'desc_max_length',
        'required_fields',
        'forbidden_terms',
        'rules_data',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'title_max_length' => 'integer',
            'bullet_max_count' => 'integer',
            'bullet_max_length' => 'integer',
            'desc_max_length' => 'integer',
            'required_fields' => 'array',
            'forbidden_terms' => 'array',
            'rules_data' => 'array',
            'is_active' => 'boolean',
        ];
    }
}
