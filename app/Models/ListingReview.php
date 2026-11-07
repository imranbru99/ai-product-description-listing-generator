<?php

namespace App\Models;

use App\Traits\BelongsToWorkspace;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListingReview extends Model
{
    use HasFactory, BelongsToWorkspace;

    protected $fillable = [
        'product_listing_id',
        'workspace_id',
        'reviewer_id',
        'status',
        'feedback',
    ];

    public function listing(): BelongsTo
    {
        return $this->belongsTo(ProductListing::class, 'product_listing_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
