<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Store an order placed through the public shopping cart.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_name' => ['nullable', 'string', 'max:255'],
            'customer_document' => ['nullable', 'string', 'max:20'],
            'customer_document_type' => ['nullable', 'in:DNI,RUC'],
            'customer_phone' => ['nullable', 'string', 'max:50'],
            'delivery_mode' => ['nullable', 'string', 'max:255'],
            'delivery_address' => ['nullable', 'string', 'max:255'],
            'payment_method' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_name' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.product_id' => ['nullable', 'exists:products,id'],
        ]);

        $validated['customer_name'] = ! empty($validated['customer_name']) ? $validated['customer_name'] : 'Cliente Web';
        $validated['customer_document'] = ! empty($validated['customer_document']) ? $validated['customer_document'] : 'S/D';
        $validated['customer_document_type'] = ! empty($validated['customer_document_type']) ? $validated['customer_document_type'] : 'DNI';
        $validated['customer_phone'] = ! empty($validated['customer_phone']) ? $validated['customer_phone'] : '-';
        $validated['delivery_mode'] = ! empty($validated['delivery_mode']) ? $validated['delivery_mode'] : 'Recojo en Tienda Moyobamba 🏪';
        $validated['payment_method'] = ! empty($validated['payment_method']) ? $validated['payment_method'] : 'Yape 📱';

        $order = DB::transaction(function () use ($validated) {
            $subtotal = 0;
            $itemsData = [];

            foreach ($validated['items'] as $item) {
                $qty = (int) $item['quantity'];
                $price = (float) $item['unit_price'];
                $lineSubtotal = $qty * $price;
                $subtotal += $lineSubtotal;

                $itemsData[] = [
                    'product_id' => $item['product_id'] ?? null,
                    'product_name' => $item['product_name'],
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'subtotal' => $lineSubtotal,
                ];
            }

            $order = Order::create([
                'order_number' => Order::generateNextNumber(),
                'customer_name' => $validated['customer_name'],
                'customer_document' => $validated['customer_document'],
                'customer_document_type' => $validated['customer_document_type'],
                'customer_phone' => $validated['customer_phone'],
                'delivery_mode' => $validated['delivery_mode'],
                'delivery_address' => $validated['delivery_address'] ?? null,
                'payment_method' => $validated['payment_method'],
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'notes' => $validated['notes'] ?? null,
                'status' => 'recibido',
            ]);

            foreach ($itemsData as $data) {
                $order->items()->create($data);
            }

            return $order;
        });

        return response()->json([
            'success' => true,
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'ticket_url' => route('orders.ticket', $order),
        ]);
    }

    /**
     * Download or view order ticket PDF.
     */
    public function ticketPdf(Order $order): Response
    {
        $order->load('items.product');
        $company = CompanySetting::getSettings();

        $pdf = Pdf::loadView('pdf.order-ticket', compact('order', 'company'))
            ->setPaper('a4', 'portrait')
            ->setOption([
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]);

        return $pdf->download("Ticket-Pedido-{$order->order_number}.pdf");
    }
}
