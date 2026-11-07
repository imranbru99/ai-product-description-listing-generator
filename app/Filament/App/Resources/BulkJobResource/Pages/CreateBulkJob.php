<?php

namespace App\Filament\App\Resources\BulkJobResource\Pages;

use App\Filament\App\Resources\BulkJobResource;
use App\Jobs\ProcessBulkJob;
use App\Models\BulkJobItem;
use App\Models\Product;
use Filament\Resources\Pages\CreateRecord;

class CreateBulkJob extends CreateRecord
{
    protected static string $resource = BulkJobResource::class;

    protected function afterCreate(): void
    {
        $job = $this->record;
        $workspace = $job->workspace;

        $selectedProducts = $this->data['products'] ?? [];
        if (empty($selectedProducts)) {
            $productIds = Product::forWorkspace($workspace->id)->pluck('id')->toArray();
        } else {
            $productIds = (array) $selectedProducts;
        }

        $total = count($productIds);
        $job->update([
            'total_items' => $total,
            'estimated_cost' => round($total * 0.0025, 4),
        ]);

        foreach ($productIds as $pId) {
            BulkJobItem::create([
                'bulk_job_id' => $job->id,
                'product_id' => $pId,
                'status' => 'pending',
            ]);
        }

        // Dispatch queued background processing
        ProcessBulkJob::dispatch($job);
    }
}
