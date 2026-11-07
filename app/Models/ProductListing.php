<?php

namespace App\Models;

use App\Traits\BelongsToWorkspace;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductListing extends Model
{
    use HasFactory, BelongsToWorkspace;

    protected $fillable = [
        'product_id',
        'workspace_id',
        'marketplace',
        'locale',
        'title',
        'short_description',
        'long_description',
        'bullet_points',
        'search_terms',
        'meta_title',
        'meta_description',
        'faqs',
        'specifications',
        'care_instructions',
        'whats_in_the_box',
        'ad_copy',
        'dpp_data',
        'social_copy',
        'status',
        'version',
        'seo_score',
        'quality_score',
        'compliance_status',
        'compliance_flags',
        'assigned_to',
        'approved_by',
        'approved_at',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'bullet_points' => 'array',
            'search_terms' => 'array',
            'faqs' => 'array',
            'specifications' => 'array',
            'ad_copy' => 'array',
            'dpp_data' => 'array',
            'social_copy' => 'array',
            'compliance_flags' => 'array',
            'approved_at' => 'datetime',
            'published_at' => 'datetime',
            'version' => 'integer',
            'seo_score' => 'integer',
            'quality_score' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(ListingVersion::class)->orderByDesc('version_number');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ListingReview::class);
    }

    /**
     * Create a new snapshot in version history.
     */
    public function snapshotVersion(?string $summary = null): ListingVersion
    {
        $nextVersion = ($this->versions()->max('version_number') ?? 0) + 1;

        return $this->versions()->create([
            'user_id' => auth()->id(),
            'version_number' => $nextVersion,
            'title' => $this->title,
            'short_description' => $this->short_description,
            'long_description' => $this->long_description,
            'bullet_points' => $this->bullet_points,
            'change_summary' => $summary ?? "Revision #{$nextVersion}",
        ]);
    }
}
