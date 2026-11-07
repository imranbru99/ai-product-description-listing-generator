<?php

namespace App\Jobs;

use App\Models\BulkJob;
use App\Services\Ai\AiGenerationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessBulkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 600;

    public function __construct(
        public BulkJob $bulkJob
    ) {}

    public function handle(AiGenerationService $aiService): void
    {
        $aiService->processBulkJob($this->bulkJob);
    }
}
