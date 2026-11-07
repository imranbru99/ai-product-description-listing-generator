<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #1f2937;
            margin: 0;
            padding: 30px;
            font-size: 13px;
            line-height: 1.5;
        }
        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .brand-title {
            font-size: 20px;
            font-weight: bold;
            color: #4f46e5;
        }
        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            text-align: right;
            color: #111827;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 25px;
        }
        .meta-table td {
            vertical-align: top;
            width: 50%;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .items-table th {
            background-color: #f3f4f6;
            color: #374151;
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #d1d5db;
        }
        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        .text-right {
            text-align: right;
        }
        .totals-table {
            width: 40%;
            margin-left: auto;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 8px 10px;
        }
        .totals-table tr.total-row {
            font-weight: bold;
            font-size: 15px;
            border-top: 2px solid #4f46e5;
            color: #4f46e5;
        }
        .badge-paid {
            display: inline-block;
            background-color: #d1fae5;
            color: #065f46;
            padding: 4px 10px;
            border-radius: 9999px;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 11px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div class="brand-title">{{ config('app.name') }}</div>
            <p>Official Billing & Invoicing Service<br>Email: billing@imrandev.bd | Web: https://imrandev.bd</p>
        </div>
        <div class="text-right">
            <div class="invoice-title">
                @if($locale === 'bn') চালানপত্র @elseif($locale === 'ar') فاتورة ضريبية @else INVOICE @endif
            </div>
            <p>
                <strong>#{{ $invoice->invoice_number }}</strong><br>
                Date: {{ $invoice->created_at->format('Y-m-d') }}<br>
                Status: <span class="badge-paid">{{ $invoice->status }}</span>
            </p>
        </div>
    </div>

    <table class="meta-table">
        <tr>
            <td>
                <strong>Billed To:</strong><br>
                {{ $workspace->name ?? 'Default Workspace' }}<br>
                Currency: {{ $invoice->currency }}<br>
                Gateway: {{ ucfirst($invoice->payment_gateway) }}
            </td>
            <td class="text-right">
                <strong>Platform Provider:</strong><br>
                Imran Ahmed (Lead Architect)<br>
                Dhaka, Bangladesh<br>
                https://imrandev.bd
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Description</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->line_items ?? [] as $item)
                <tr>
                    <td>{{ $item['description'] ?? 'Service charge' }}</td>
                    <td class="text-right">{{ $item['qty'] ?? 1 }}</td>
                    <td class="text-right">{{ $invoice->currency }} {{ number_format($item['price'] ?? 0, 2) }}</td>
                    <td class="text-right">{{ $invoice->currency }} {{ number_format(($item['qty'] ?? 1) * ($item['price'] ?? 0), 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td>Subtotal:</td>
            <td class="text-right">{{ $invoice->currency }} {{ number_format($invoice->amount - $invoice->tax, 2) }}</td>
        </tr>
        <tr>
            <td>Tax (0%):</td>
            <td class="text-right">{{ $invoice->currency }} {{ number_format($invoice->tax, 2) }}</td>
        </tr>
        <tr class="total-row">
            <td>Total:</td>
            <td class="text-right">{{ $invoice->currency }} {{ number_format($invoice->amount, 2) }}</td>
        </tr>
    </table>

    <div class="footer">
        Generated autonomously by {{ config('app.name') }} on {{ now()->toDateTimeString() }}.<br>
        Thank you for your business. For questions contact me@imrandev.bd.
    </div>
</body>
</html>
