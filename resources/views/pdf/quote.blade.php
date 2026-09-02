<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cotización {{ $quote->quote_number }} - {{ $company->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }
        body {
            font-size: 11px;
            color: #1e293b;
            padding: 28px 32px;
            background: #ffffff;
        }
        .header {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 12px;
        }
        .company-col {
            width: 58%;
            float: left;
        }
        .quote-box {
            width: 38%;
            float: right;
            border: 2px solid #2563eb;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            background: #eff6ff;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .company-detail {
            font-size: 10px;
            color: #475569;
            line-height: 1.4;
        }
        .quote-title {
            font-size: 14px;
            font-weight: bold;
            color: #1d4ed8;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .quote-num {
            font-size: 15px;
            font-weight: bold;
            color: #0f172a;
            margin-top: 4px;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        .client-card {
            width: 100%;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 14px;
            margin-bottom: 18px;
        }
        .client-col {
            width: 50%;
            float: left;
        }
        .client-row {
            margin-bottom: 4px;
            font-size: 10.5px;
        }
        .client-label {
            font-weight: bold;
            color: #64748b;
            width: 110px;
            display: inline-block;
        }
        .client-val {
            color: #0f172a;
            font-weight: 600;
        }
        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        table.items-table th {
            background: #1e293b;
            color: #ffffff;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 8px 10px;
            text-align: left;
            border: 1px solid #1e293b;
        }
        table.items-table td {
            padding: 8px 10px;
            font-size: 10.5px;
            border: 1px solid #cbd5e1;
            color: #334155;
        }
        table.items-table tr:nth-child(even) td {
            background: #f8fafc;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .totals-section {
            width: 100%;
            margin-bottom: 20px;
        }
        .conditions-col {
            width: 55%;
            float: left;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 6px;
            padding: 10px 12px;
            font-size: 9.5px;
            line-height: 1.45;
            color: #475569;
        }
        .totals-col {
            width: 40%;
            float: right;
        }
        table.totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        table.totals-table td {
            padding: 5px 8px;
            font-size: 11px;
        }
        table.totals-table tr.total-row td {
            background: #2563eb;
            color: #ffffff;
            font-weight: bold;
            font-size: 13px;
            border-radius: 4px;
        }
        .signatures {
            width: 100%;
            margin-top: 36px;
            padding-top: 10px;
        }
        .sig-box {
            width: 45%;
            float: left;
            text-align: center;
            border-top: 1px solid #94a3b8;
            padding-top: 6px;
            font-size: 10px;
            color: #64748b;
        }
        .footer-note {
            margin-top: 25px;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header clearfix">
        <div class="company-col">
            <div class="company-name">{{ $company->name }}</div>
            <div class="company-detail">
                @if($company->address)
                    <span>📍 {{ $company->address }}</span><br>
                @endif
                <span>🏛️ {{ $company->city_province ?? 'Moyobamba' }}, {{ $company->region ?? 'San Martín' }} - Perú</span><br>
                @if($company->phone || $company->whatsapp)
                    <span>📞 Teléfono: {{ $company->phone ?? $company->whatsapp }}</span><br>
                @endif
                @if($company->email)
                    <span>✉️ Correo: {{ $company->email }}</span>
                @endif
            </div>
        </div>
        <div class="quote-box">
            <div class="quote-title">Cotización Comercial</div>
            <div class="quote-num">{{ $quote->quote_number }}</div>
            <div style="font-size: 9.5px; color: #475569; margin-top: 4px;">
                Fecha: <strong>{{ $quote->created_at->format('d/m/Y') }}</strong><br>
                Validez: <strong>{{ $quote->validity_days }} días</strong>
            </div>
        </div>
    </div>

    <!-- Client Info -->
    <div class="client-card clearfix">
        <div class="client-col">
            <div class="client-row">
                <span class="client-label">Cliente / Razón Social:</span>
                <span class="client-val">{{ $quote->customer_name }}</span>
            </div>
            <div class="client-row">
                <span class="client-label">{{ $quote->customer_document_type }}:</span>
                <span class="client-val">{{ $quote->customer_document ?: 'No especificado' }}</span>
            </div>
            <div class="client-row">
                <span class="client-label">Ciudad / Destino:</span>
                <span class="client-val">{{ $quote->city ?: 'Moyobamba' }}</span>
            </div>
        </div>
        <div class="client-col">
            <div class="client-row">
                <span class="client-label">Teléfono / Celular:</span>
                <span class="client-val">{{ $quote->customer_phone ?: '-' }}</span>
            </div>
            <div class="client-row">
                <span class="client-label">Correo Electrónico:</span>
                <span class="client-val">{{ $quote->customer_email ?: '-' }}</span>
            </div>
            <div class="client-row">
                <span class="client-label">Dirección:</span>
                <span class="client-val">{{ $quote->customer_address ?: '-' }}</span>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 5%;">#</th>
                <th style="width: 55%;">Descripción del Producto / Equipo</th>
                <th class="text-center" style="width: 12%;">Cant.</th>
                <th class="text-right" style="width: 14%;">P. Unitario</th>
                <th class="text-right" style="width: 14%;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quote->items as $index => $item)
                <tr>
                    <td class="text-center font-bold">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item->product_name }}</strong>
                        @if($item->product && $item->product->description)
                            <div style="font-size: 9px; color: #64748b; margin-top: 2px;">
                                {{ Str::limit(strip_tags($item->product->description), 85) }}
                            </div>
                        @endif
                    </td>
                    <td class="text-center font-bold">{{ $item->quantity }}</td>
                    <td class="text-right">S/ {{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right font-bold">S/ {{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals & Terms -->
    <div class="totals-section clearfix">
        <div class="conditions-col">
            <strong style="color: #1e293b; font-size: 10px; text-transform: uppercase;">Condiciones Comerciales:</strong>
            <ul style="padding-left: 14px; margin-top: 4px;">
                <li>Precios expresados en <strong>Soles Peruanos (S/)</strong>.</li>
                <li>Garantía de productos sujeta a políticas del fabricante y la empresa.</li>
                <li>Formas de Pago: <strong>Yape, Plin y Transferencia Bancaria (BCP / BBVA / Interbank)</strong>.</li>
                @if($quote->notes)
                    <li style="color: #1d4ed8; font-weight: bold; margin-top: 3px;">Nota: {{ $quote->notes }}</li>
                @endif
            </ul>
        </div>

        <div class="totals-col">
            <table class="totals-table">
                <tr>
                    <td class="text-right" style="color: #64748b;">Subtotal:</td>
                    <td class="text-right font-bold">S/ {{ number_format($quote->subtotal, 2) }}</td>
                </tr>
                @if($quote->discount > 0)
                    <tr>
                        <td class="text-right" style="color: #dc2626;">Descuento:</td>
                        <td class="text-right font-bold" style="color: #dc2626;">- S/ {{ number_format($quote->discount, 2) }}</td>
                    </tr>
                @endif
                <tr class="total-row">
                    <td class="text-right">TOTAL A PAGAR:</td>
                    <td class="text-right">S/ {{ number_format($quote->total, 2) }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Signatures -->
    <div class="signatures clearfix">
        <div class="sig-box" style="float: left;">
            <strong>{{ $company->name }}</strong><br>
            Área de Ventas y Cotizaciones
        </div>
        <div class="sig-box" style="float: right;">
            <strong>Conformidad del Cliente</strong><br>
            Firma / Sello
        </div>
    </div>

    <div class="footer-note">
        Esta cotización es un documento informativo emitido por {{ $company->name }} en Moyobamba, San Martín. Agradecemos su preferencia.
    </div>

</body>
</html>
