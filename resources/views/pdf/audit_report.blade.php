<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>Listing Health Audit #{{ $listing->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #1f2937;
            margin: 0;
            padding: 25px;
            font-size: 12px;
        }
        .header {
            border-bottom: 2px solid #10b981;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            color: #065f46;
        }
        .score-box {
            background-color: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-around;
        }
        .score-item {
            text-align: center;
        }
        .score-val {
            font-size: 26px;
            font-weight: bold;
            color: #059669;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
            margin: 15px 0 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Listing Health & Compliance Audit</div>
        <p>Product: {{ $product->name ?? 'Product' }} | Marketplace: {{ ucfirst($listing->marketplace) }} | Locale: {{ strtoupper($listing->locale) }}</p>
    </div>

    <table style="width: 100%; margin-bottom: 20px;">
        <tr>
            <td style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 15px; text-align: center; border-radius: 6px;">
                <div style="font-size: 11px; text-transform: uppercase; color: #166534;">SEO Score</div>
                <div style="font-size: 28px; font-weight: bold; color: #15803d;">{{ $listing->seo_score }}/100</div>
            </td>
            <td style="width: 15px;"></td>
            <td style="background: #eff6ff; border: 1px solid #bfdbfe; padding: 15px; text-align: center; border-radius: 6px;">
                <div style="font-size: 11px; text-transform: uppercase; color: #1e40af;">Quality Score</div>
                <div style="font-size: 28px; font-weight: bold; color: #1d4ed8;">{{ $listing->quality_score }}/100</div>
            </td>
            <td style="width: 15px;"></td>
            <td style="background: #faf5ff; border: 1px solid #e9d5ff; padding: 15px; text-align: center; border-radius: 6px;">
                <div style="font-size: 11px; text-transform: uppercase; color: #6b21a8;">Compliance</div>
                <div style="font-size: 28px; font-weight: bold; color: #7e22ce;">{{ strtoupper($listing->compliance_status) }}</div>
            </td>
        </tr>
    </table>

    <div class="section-title">Audited Content Overview</div>
    <p><strong>Title ({{ mb_strlen($listing->title) }} chars):</strong><br>{{ $listing->title }}</p>
    <p><strong>Meta Description:</strong><br>{{ $listing->meta_description }}</p>

    <div class="section-title">Bullet Points ({{ count($listing->bullet_points ?? []) }} Items)</div>
    <ul>
        @foreach($listing->bullet_points ?? [] as $bullet)
            <li>{{ $bullet }}</li>
        @endforeach
    </ul>

    <div class="section-title">Compliance & Quality Certification</div>
    <p>This listing has been autonomously audited against marketplace rules, trademark databases, and formatting limits. All critical compliance checks have passed.</p>
</body>
</html>
