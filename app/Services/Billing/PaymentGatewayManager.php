<?php

namespace App\Services\Billing;

use App\Services\Billing\Contracts\PaymentGatewayInterface;
use App\Services\Billing\Gateways\FakePaymentGateway;
use App\Services\Billing\Gateways\StripePaymentGateway;
use InvalidArgumentException;

class PaymentGatewayManager
{
    protected array $gateways = [];

    public function __construct()
    {
        $this->register('fake', new FakePaymentGateway());
        $this->register('stripe', new StripePaymentGateway());
    }

    public function register(string $name, PaymentGatewayInterface $gateway): void
    {
        $this->gateways[$name] = $gateway;
    }

    public function gateway(?string $name = null): PaymentGatewayInterface
    {
        $name = $name ?? config('services.billing.default', 'fake');

        if (!isset($this->gateways[$name])) {
            return $this->gateways['fake'];
        }

        return $this->gateways[$name];
    }
}
