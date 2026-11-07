<?php

namespace App\Services\Pdf;

use App\Models\Invoice;
use App\Models\ProductListing;
use App\Models\Workspace;
use Illuminate\Support\Facades\Storage;
use ImranDev\UnicodePdf\Facades\UnicodePdf;

class UnicodePdfService
{
    /**
     * Generate an invoice PDF in the given locale (Bengali, Arabic, English, etc.)
     */
    public function generateInvoicePdf(Invoice $invoice, ?string $locale = null): string
    {
        $locale = $locale ?? $invoice->workspace?->locale ?? 'en';
        $preset = match ($locale) {
            'bn' => 'bengali',
            'ar' => 'arabic',
            default => 'default',
        };

        $pdf = UnicodePdf::preset($preset)
            ->loadView('pdf.invoice', [
                'invoice' => $invoice,
                'workspace' => $invoice->workspace,
                'locale' => $locale,
            ])
            ->a4();

        $filename = "invoices/{$invoice->invoice_number}.pdf";
        Storage::disk('public')->put($filename, $pdf->output());

        $invoice->update(['pdf_path' => $filename]);

        return $filename;
    }

    /**
     * Generate a multi-product catalog line sheet in any language.
     */
    public function generateCatalogPdf(Workspace $workspace, ?array $productIds = null, string $locale = 'en'): string
    {
        $query = $workspace->products()->with(['brand', 'category', 'listings']);
        if (!empty($productIds)) {
            $query->whereIn('id', $productIds);
        }
        $products = $query->take(20)->get();

        $preset = match ($locale) {
            'bn' => 'bengali',
            'ar' => 'arabic',
            default => 'default',
        };

        $pdf = UnicodePdf::preset($preset)
            ->loadView('pdf.catalog', [
                'workspace' => $workspace,
                'products' => $products,
                'locale' => $locale,
            ])
            ->a4();

        $filename = "catalogs/{$workspace->slug}-catalog-" . date('Ymd-His') . ".pdf";
        Storage::disk('public')->put($filename, $pdf->output());

        return $filename;
    }

    /**
     * Generate a Content Health Audit Report PDF.
     */
    public function generateAuditReportPdf(ProductListing $listing, string $locale = 'en'): string
    {
        $preset = match ($locale) {
            'bn' => 'bengali',
            'ar' => 'arabic',
            default => 'default',
        };

        $pdf = UnicodePdf::preset($preset)
            ->loadView('pdf.audit_report', [
                'listing' => $listing,
                'product' => $listing->product,
                'workspace' => $listing->workspace,
                'locale' => $locale,
            ])
            ->a4();

        $filename = "audits/listing-{$listing->id}-audit.pdf";
        Storage::disk('public')->put($filename, $pdf->output());

        return $filename;
    }
}
