<?php

namespace App\Services\Billing\Gateways;

use App\Models\Invoice;
use App\Models\Workspace;
use App\Services\Billing\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Str;

class FakePaymentGateway implements PaymentGatewayInterface
{
    public function getName(): string
    {
        return 'fake';
    }

    public function charge(Workspace $workspace, float $amount, string $currency, array $details = []): array
    {
        $transactionId = 'FAKE-TXN-' . strtoupper(Str::random(12));

        $invoice = Invoice::create([
            'workspace_id' => $workspace->id,
            'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
            'status' => 'paid',
            'amount' => $amount,
            'tax' => 0.00,
            'currency' => $currency,
            'line_items' => $details['items'] ?? [
                ['description' => $details['description'] ?? 'Credit Pack Purchase', 'qty' => 1, 'price' => $amount],
            ],
            'payment_gateway' => 'fake',
            'transaction_id' => $transactionId,
            'paid_at' => now(),
        ]);

        // Add credits if purchasing credit pack
        if (!empty($details['credits'])) {
            $workspace->addCredits((int) $details['credits'], "Purchased {$details['credits']} AI credits", $invoice);
        }

        return [
            'success' => true,
            'transaction_id' => $transactionId,
            'invoice_id' => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
            'amount' => $amount,
            'currency' => $currency,
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
