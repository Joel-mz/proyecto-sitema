<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Productos - {{ $company->name ?? 'TechStore' }}</title>
    <style>
        @page {
            margin: 25px 30px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            font-size: 11px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 2px solid #2563eb;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .company-logo-cell {
            width: 120px;
            vertical-align: middle;
        }
        .company-logo {
            max-width: 110px;
            max-height: 55px;
            object-fit: contain;
        }
        .company-name-text {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }
        .catalog-title {
            color: #2563eb;
            font-size: 12px;
            font-weight: bold;
            margin: 2px 0 4px 0;
            text-transform: uppercase;
        }
        .company-info {
            font-size: 8.5px;
            color: #475569;
            line-height: 1.3;
        }
        .category-title {
            background-color: #0f172a;
            color: #ffffff;
            padding: 6px 12px;
            font-size: 11.5px;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 4px;
            margin-top: 18px;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }
        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .product-row {
            border-bottom: 1px solid #e2e8f0;
            page-break-inside: avoid;
        }
        .product-image-cell {
            width: 75px;
            padding: 8px 6px;
            text-align: center;
            vertical-align: middle;
        }
        .product-image {
            width: 65px;
            height: 65px;
            object-fit: contain;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            background-color: #f8fafc;
        }
        .no-image {
            width: 65px;
            height: 65px;
            background-color: #f1f5f9;
            border: 1px dashed #cbd5e1;
            border-radius: 4px;
            display: inline-block;
            line-height: 65px;
            font-size: 8px;
            color: #94a3b8;
            text-align: center;
        }
        .product-info-cell {
            padding: 8px 12px;
            vertical-align: middle;
        }
        .product-name {
            font-size: 11.5px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 3px;
        }
        .product-desc {
            font-size: 9.5px;
            color: #475569;
            margin: 0;
            line-height: 1.3;
        }
        .product-price-cell {
            width: 95px;
            padding: 8px 6px;
            text-align: right;
            vertical-align: middle;
        }
        .price-label {
            font-size: 7.5px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: bold;
            display: block;
        }
        .price-value {
            font-size: 12.5px;
            font-weight: bold;
            color: #2563eb;
            white-space: nowrap;
        }
        .footer {
            position: fixed;
            bottom: -15px;
            left: 0;
            right: 0;
            height: 20px;
            text-align: center;
            font-size: 8px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 4px;
        }
    </style>
</head>
<body>

    <div class="header">
        <table class="header-table">
            <tr>
                @if(isset($company) && $company->logo && file_exists(public_path('storage/' . $company->logo)))
                    <td class="company-logo-cell">
                        <img src="{{ public_path('storage/' . $company->logo) }}" class="company-logo" alt="{{ $company->name }}">
                    </td>
                @endif
                <td>
                    <h1 class="company-name-text">{{ $company->name ?? 'TECHSTORE' }}</h1>
                    <div class="catalog-title">Catálogo Oficial de Productos</div>
                    <div class="company-info">
                        @if($company->address)
                            <strong>Ubicación:</strong> {{ $company->address }}
                            @if($company->city_province || $company->region)
                                ({{ $company->city_province ?? '' }}{{ $company->region ? ' - ' . $company->region : '' }})
                            @endif
                            <br>
                        @endif
                        @if($company->phone || $company->whatsapp || $company->email)
                            <strong>Contacto:</strong>
                            {{ $company->phone ? 'Tel: ' . $company->phone : '' }}
                            {{ $company->whatsapp ? ' • WhatsApp: ' . $company->whatsapp : '' }}
                            {{ $company->email ? ' • ' . $company->email : '' }}
                        @endif
                    </div>
                </td>
                <td style="text-align: right; vertical-align: top; width: 120px;">
                    <span style="font-size: 8px; color: #64748b; text-transform: uppercase; font-weight: bold;">Fecha de Emisión:</span><br>
                    <strong style="font-size: 10px; color: #0f172a;">{{ date('d/m/Y') }}</strong>
                </td>
            </tr>
        </table>
    </div>

    @foreach($categories as $cat)
        @if($cat->products->count() > 0)
            <div class="category-title">
                {{ $cat->name }}
            </div>

            <table class="product-table">
                <tbody>
                    @foreach($cat->products as $prod)
                        <tr class="product-row">
                            <td class="product-image-cell">
                                @if($prod->image && str_starts_with($prod->image, 'http'))
                                    <img src="{{ $prod->image }}" class="product-image" alt="{{ $prod->name }}">
                                @elseif($prod->image && file_exists(public_path('storage/' . $prod->image)))
                                    <img src="{{ public_path('storage/' . $prod->image) }}" class="product-image" alt="{{ $prod->name }}">
                                @else
                                    <div class="no-image">Sin foto</div>
                                @endif
                            </td>
                            <td class="product-info-cell">
                                <div class="product-name">{{ $prod->name }}</div>
                                <p class="product-desc">{{ $prod->description ?: 'Producto en óptimas condiciones.' }}</p>
                            </td>
                            <td class="product-price-cell">
                                <span class="price-label">Precio</span>
                                <span class="price-value">S/ {{ number_format($prod->price, 2) }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endforeach

    <div class="footer">
        {{ $company->name ?? 'TechStore' }} • Catálogo de Productos • Precios sujetos a variación sin previo aviso
    </div>

</body>
</html>
