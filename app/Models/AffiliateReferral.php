<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliateReferral extends Model
{
    use HasFactory;

    protected $fillable = [
        'affiliate_id',
        'referred_workspace_id',
        'commission_amount',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'commission_amount' => 'decimal:2',
        ];
    }

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class, 'referred_workspace_id');
    }
}
