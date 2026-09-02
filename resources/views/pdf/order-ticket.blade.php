<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket de Pedido {{ $order->order_number }} - {{ $company->name }}</title>
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
            padding: 24px 28px;
            background: #ffffff;
        }
        .header {
            text-align: center;
            border-bottom: 2px dashed #94a3b8;
            padding-bottom: 12px;
            margin-bottom: 14px;
        }
        .company-name {
            font-size: 17px;
            font-weight: bold;
            color: #0f172a;
        }
        .company-info {
            font-size: 10px;
            color: #64748b;
            margin-top: 3px;
            line-height: 1.35;
        }
        .ticket-title {
            font-size: 13px;
            font-weight: bold;
            color: #16a34a;
            margin-top: 6px;
            text-transform: uppercase;
        }
        .ticket-number {
            font-size: 15px;
            font-weight: bold;
            color: #0f172a;
        }
        .info-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 8px 12px;
            margin-bottom: 14px;
            font-size: 10.5px;
        }
        .info-row {
            margin-bottom: 3px;
        }
        .info-label {
            font-weight: bold;
            color: #64748b;
            width: 120px;
            display: inline-block;
        }
        .info-val {
            font-weight: 600;
            color: #0f172a;
        }
        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        table.items-table th {
            background: #0f172a;
            color: #ffffff;
            font-size: 10px;
            font-weight: bold;
            padding: 6px 8px;
            text-transform: uppercase;
        }
        table.items-table td {
            padding: 6px 8px;
            font-size: 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-box {
            background: #f0fdf4;
            border: 2px solid #16a34a;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 14px;
            text-align: right;
        }
        .total-label {
            font-size: 12px;
            font-weight: bold;
            color: #166534;
        }
        .total-amount {
            font-size: 18px;
            font-weight: bold;
            color: #15803d;
        }
        .payment-box {
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 10px;
            color: #475569;
            margin-bottom: 12px;
        }
        .footer-text {
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            margin-top: 10px;
            border-top: 1px dashed #cbd5e1;
            padding-top: 8px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <div class="company-name">{{ $company->name }}</div>
        <div class="company-info">
            {{ $company->address ?? 'Moyobamba, San Martín, Perú' }}<br>
            @if($company->phone || $company->whatsapp)
                Tel/WhatsApp: {{ $company->phone ?? $company->whatsapp }}<br>
            @endif
            @if($company->email)
                Email: {{ $company->email }}
            @endif
        </div>
        <div class="ticket-title">Comprobante de Pedido / Ticket</div>
        <div class="ticket-number">{{ $order->order_number }}</div>
        <div style="font-size: 9.5px; color: #64748b; margin-top: 2px;">
            Fecha de Registro: {{ $order->created_at->format('d/m/Y H:i A') }}
        </div>
    </div>

    <!-- Client & Delivery Details -->
    <div class="info-card">
        <div class="info-row">
            <span class="info-label">Cliente / Razón Social:</span>
            <span class="info-val">{{ $order->customer_name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">{{ $order->customer_document_type }}:</span>
            <span class="info-val">{{ $order->customer_document }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Teléfono de Contacto:</span>
            <span class="info-val">{{ $order->customer_phone }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Modalidad de Entrega:</span>
            <span class="info-val" style="color: #2563eb;">{{ $order->delivery_mode }}</span>
        </div>
        @if($order->delivery_address)
            <div class="info-row">
                <span class="info-label">Dirección de Entrega:</span>
                <span class="info-val">{{ $order->delivery_address }}</span>
            </div>
        @endif
        <div class="info-row">
            <span class="info-label">Método de Pago:</span>
            <span class="info-val" style="color: #7c3aed;">{{ $order->payment_method }}</span>
        </div>
        @if($order->notes)
            <div class="info-row">
                <span class="info-label">Notas del Pedido:</span>
                <span class="info-val">{{ $order->notes }}</span>
            </div>
        @endif
    </div>

    <!-- Products Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 8%;">#</th>
                <th style="width: 54%;">Producto</th>
                <th class="text-center" style="width: 12%;">Cant.</th>
                <th class="text-right" style="width: 13%;">P. Unit</th>
                <th class="text-right" style="width: 13%;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $index => $item)
                <tr>
                    <td class="text-center font-bold">{{ $index + 1 }}</td>
                    <td><strong>{{ $item->product_name }}</strong></td>
                    <td class="text-center font-bold">{{ $item->quantity }}</td>
                    <td class="text-right">S/ {{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right font-bold">S/ {{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Total Box -->
    <div class="total-box">
        <span class="total-label">TOTAL A PAGAR: </span>
        <span class="total-amount">S/ {{ number_format($order->total, 2) }}</span>
    </div>

    <!-- Payment Methods Info -->
    <div class="payment-box">
        <strong>Información de Pago ({{ $order->payment_method }}):</strong><br>
        • Por favor envía la constancia de transferencia o captura de Yape/Plin al WhatsApp <strong>{{ $company->whatsapp ?? '+51 987 654 321' }}</strong>.<br>
        • Tu pedido será preparado y despachado de inmediato tras la confirmación de pago.
    </div>

    <div class="footer-text">
        ¡Gracias por tu compra en {{ $company->name }}!<br>
        Moyobamba, San Martín - Perú
    </div>

</body>
</html>
