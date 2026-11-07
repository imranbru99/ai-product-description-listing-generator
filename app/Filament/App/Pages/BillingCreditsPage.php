<?php

namespace App\Filament\App\Pages;

use App\Models\Invoice;
use App\Models\Workspace;
use App\Services\Billing\PaymentGatewayManager;
use App\Services\Pdf\UnicodePdfService;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class BillingCreditsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Settings & Operations';
    protected static ?int $navigationSort = 11;
    protected static ?string $navigationLabel = 'Billing & Credits';
    protected static ?string $title = 'Billing, Credits & Invoices';
    protected static string $view = 'filament.app.pages.billing-credits-page';

    public int $creditsBalance = 0;
    public string $selectedGateway = 'fake';

    public function mount(): void
    {
        $this->refreshBalance();
    }

    protected function refreshBalance(): void
    {
        $workspace = Filament::getTenant();
        $this->creditsBalance = $workspace->credits_balance;
    }

    public function buyPack(int $credits, float $price, PaymentGatewayManager $gatewayManager): void
    {
        $workspace = Filament::getTenant();
        $gateway = $gatewayManager->gateway($this->selectedGateway);

        $result = $gateway->charge($workspace, $price, $workspace->currency ?? 'USD', [
            'credits' => $credits,
            'description' => "Purchase of {$credits} AI Content Generation Credits",
            'items' => [
                ['description' => "{$credits} AI Credits Pack", 'qty' => 1, 'price' => $price],
            ],
        ]);

        if ($result['success']) {
            $this->refreshBalance();

            Notification::make()
                ->title('Payment Successful!')
                ->body("Successfully added {$credits} credits to your workspace. Invoice #{$result['invoice_number']} generated.")
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Payment Failed')
                ->body('Unable to process transaction. Please try again.')
                ->danger()
                ->send();
        }
    }

    public function downloadInvoice(int $invoiceId, UnicodePdfService $pdfService)
    {
        $invoice = Invoice::findOrFail($invoiceId);
        $path = $pdfService->generateInvoicePdf($invoice, $invoice->workspace?->locale ?? 'en');

        return response()->download(storage_path('app/public/' . $path));
    }
}
