<?php

namespace App\Services\Billing\Contracts;

use App\Models\Workspace;

interface PaymentGatewayInterface
{
    public function getName(): string;

    public function charge(Workspace $workspace, float $amount, string $currency, array $details = []): array;

    public function verifyPayment(string $transactionId): bool;

    public function refund(string $transactionId, float $amount): bool;
}
