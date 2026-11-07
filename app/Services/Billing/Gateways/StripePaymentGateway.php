<?php

namespace App\Services\Billing\Gateways;

use App\Models\Invoice;
use App\Models\Workspace;
use App\Services\Billing\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Str;

class StripePaymentGateway implements PaymentGatewayInterface
{
    public function getName(): string
    {
        return 'stripe';
    }

    public function charge(Workspace $workspace, float $amount, string $currency, array $details = []): array
    {
        $transactionId = 'ch_' . strtolower(Str::random(24));

        $invoice = Invoice::create([
            'workspace_id' => $workspace->id,
            'invoice_number' => 'INV-STRIPE-' . strtoupper(Str::random(8)),
            'status' => 'paid',
            'amount' => $amount,
            'tax' => 0.00,
            'currency' => $currency,
            'line_items' => $details['items'] ?? [
                ['description' => $details['description'] ?? 'Subscription Payment', 'qty' => 1, 'price' => $amount],
            ],
            'payment_gateway' => 'stripe',
            'transaction_id' => $transactionId,
            'paid_at' => now(),
        ]);

        return [
            'success' => true,
            'transaction_id' => $transactionId,
            'invoice_id' => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
        ];
    }

    public function verifyPayment(string $transactionId): bool
    {
        return true;
    }

    public function refund(string $transactionId, float $amount): bool
    {
        return true;
    }
}
