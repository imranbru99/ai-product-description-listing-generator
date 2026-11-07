<?php

namespace App\Models;

use App\Traits\BelongsToWorkspace;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory, BelongsToWorkspace;

    protected $fillable = [
        'workspace_id',
        'invoice_number',
        'status',
        'amount',
        'tax',
        'currency',
        'line_items',
        'payment_gateway',
        'transaction_id',
        'pdf_path',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'tax' => 'decimal:2',
            'line_items' => 'array',
            'paid_at' => 'datetime',
        ];
    }
}
