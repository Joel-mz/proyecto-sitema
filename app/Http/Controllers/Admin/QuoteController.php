<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use App\Models\Product;
use App\Models\Quote;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class QuoteController extends Controller
{
    /**
     * Display a listing of quotes.
     */
    public function index(Request $request): View
    {
        $query = Quote::with(['user', 'items'])->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('quote_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_document', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $quotes = $query->paginate(15)->withQueryString();

        return view('admin.quotes.index', compact('quotes'));
    }

    /**
     * Show the form for creating a new quote.
     */
    public function create(): View
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $nextNumber = Quote::generateNextNumber();
        $company = CompanySetting::getSettings();

        return view('admin.quotes.create', compact('products', 'nextNumber', 'company'));
    }

    /**
     * Store a newly created quote in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_document' => ['nullable', 'string', 'max:20'],
            'customer_document_type' => ['required', 'in:DNI,RUC'],
            'customer_phone' => ['nullable', 'string', 'max:50'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'validity_days' => ['required', 'integer', 'min:1', 'max:180'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_name' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.product_id' => ['nullable', 'exists:products,id'],
            'discount' => ['nullable', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($validated, &$quote) {
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

            $discount = (float) ($validated['discount'] ?? 0);
            $total = max(0, $subtotal - $discount);

            $quote = Quote::create([
                'quote_number' => Quote::generateNextNumber(),
                'customer_name' => $validated['customer_name'],
                'customer_document' => $validated['customer_document'] ?? null,
                'customer_document_type' => $validated['customer_document_type'],
                'customer_phone' => $validated['customer_phone'] ?? null,
                'customer_email' => $validated['customer_email'] ?? null,
                'customer_address' => $validated['customer_address'] ?? null,
                'city' => ($validated['city'] ?? '') ?: 'Moyobamba',
                'validity_days' => (int) $validated['validity_days'],
                'status' => 'pendiente',
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'notes' => $validated['notes'] ?? null,
                'user_id' => auth()->id(),
            ]);

            foreach ($itemsData as $data) {
                $quote->items()->create($data);
            }
        });

        return redirect()->route('quotes.show', $quote)
            ->with('success', "Cotización {$quote->quote_number} generada correctamente.");
    }

    /**
     * Display the specified quote.
     */
    public function show(Quote $quote): View
    {
        $quote->load(['items.product', 'user']);
        $company = CompanySetting::getSettings();

        return view('admin.quotes.show', compact('quote', 'company'));
    }

    /**
     * Update the status of a quote.
     */
    public function updateStatus(Request $request, Quote $quote): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pendiente,aprobada,rechazada,facturada'],
        ]);

        $quote->update(['status' => $validated['status']]);

        return back()->with('success', 'Estado de la cotización actualizado.');
    }

    /**
     * Remove the specified quote from storage.
     */
    public function destroy(Quote $quote): RedirectResponse
    {
        $number = $quote->quote_number;
        $quote->delete();

        return redirect()->route('quotes.index')
            ->with('success', "Cotización {$number} eliminada correctamente.");
    }

    /**
     * Generate formal PDF proforma for download/print.
     */
    public function pdf(Quote $quote): Response
    {
        $quote->load(['items.product', 'user']);
        $company = CompanySetting::getSettings();

        $pdf = Pdf::loadView('pdf.quote', compact('quote', 'company'))
            ->setPaper('a4', 'portrait')
            ->setOption([
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]);

        return $pdf->download("Cotizacion-{$quote->quote_number}.pdf");
    }
}
