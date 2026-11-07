<?php

namespace App\Services\Webhook;

use App\Models\Webhook;
use App\Models\WebhookDelivery;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookDispatcher
{
    /**
     * Dispatch an event to all matching webhooks for a workspace.
     */
    public static function dispatch(string $event, array $payload, ?int $workspaceId = null): void
    {
        $query = Webhook::where('is_active', true);
        if ($workspaceId) {
            $query->where('workspace_id', $workspaceId);
        }

        $webhooks = $query->get();

        foreach ($webhooks as $webhook) {
            if (!empty($webhook->events) && !in_array('*', $webhook->events, true) && !in_array($event, $webhook->events, true)) {
                continue;
            }

            static::send($webhook, $event, $payload);
        }
    }

    /**
     * Send payload to a single webhook endpoint and log delivery.
     */
    public static function send(Webhook $webhook, string $event, array $payload): WebhookDelivery
    {
        $jsonPayload = json_encode([
            'event' => $event,
            'timestamp' => now()->toIso8601String(),
            'data' => $payload,
        ]);

        $signature = hash_hmac('sha256', $jsonPayload, $webhook->secret);

        $statusCode = null;
        $responseBody = null;
        $success = false;

        try {
            $response = Http::timeout(5)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'X-Webhook-Event' => $event,
                    'X-Webhook-Signature' => $signature,
                    'User-Agent' => 'AI-Listing-Generator-Webhook/1.0',
                ])
                ->post($webhook->url, json_decode($jsonPayload, true));

            $statusCode = $response->status();
            $responseBody = substr($response->body(), 0, 1000);
            $success = $response->successful();
        } catch (\Throwable $e) {
            $responseBody = $e->getMessage();
            Log::warning("Webhook delivery failed to {$webhook->url}: " . $e->getMessage());
        }

        return WebhookDelivery::create([
            'webhook_id' => $webhook->id,
            'event' => $event,
            'payload' => json_decode($jsonPayload, true),
            'status_code' => $statusCode,
            'response_body' => $responseBody,
            'success' => $success,
            'attempt' => 1,
            'created_at' => now(),
        ]);
    }
}
