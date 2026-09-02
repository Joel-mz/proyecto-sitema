<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuoteAndOrderTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $category = Category::create([
            'name' => 'Laptops',
            'slug' => 'laptops',
        ]);

        $this->product = Product::create([
            'category_id' => $category->id,
            'name' => 'Laptop ASUS ROG',
            'slug' => 'laptop-asus-rog',
            'price' => 3500.00,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_view_quotes_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('quotes.index'));

        $response->assertStatus(200);
        $response->assertSee('Cotizaciones Registradas');
    }

    public function test_admin_can_create_quote(): void
    {
        $payload = [
            'customer_name' => 'Juan Perez',
            'customer_document' => '74859612',
            'customer_document_type' => 'DNI',
            'customer_phone' => '987654321',
            'customer_email' => 'juan@example.com',
            'city' => 'Moyobamba',
            'validity_days' => 15,
            'notes' => 'Garantia de 1 año',
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'product_name' => $this->product->name,
                    'quantity' => 2,
                    'unit_price' => 3500.00,
                ],
            ],
            'discount' => 200.00,
        ];

        $response = $this->actingAs($this->admin)->post(route('quotes.store'), $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('quotes', [
            'customer_name' => 'Juan Perez',
            'subtotal' => 7000.00,
            'discount' => 200.00,
            'total' => 6800.00,
        ]);

        $this->assertDatabaseHas('quote_items', [
            'product_name' => 'Laptop ASUS ROG',
            'quantity' => 2,
            'subtotal' => 7000.00,
        ]);
    }

    public function test_admin_can_download_quote_pdf(): void
    {
        $quote = Quote::create([
            'quote_number' => Quote::generateNextNumber(),
            'customer_name' => 'Empresa San Martin SAC',
            'customer_document' => '20601234567',
            'customer_document_type' => 'RUC',
            'customer_phone' => '942000000',
            'city' => 'Moyobamba',
            'validity_days' => 30,
            'subtotal' => 3500.00,
            'total' => 3500.00,
            'status' => 'pendiente',
            'user_id' => $this->admin->id,
        ]);

        $quote->items()->create([
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'quantity' => 1,
            'unit_price' => 3500.00,
            'subtotal' => 3500.00,
        ]);

        $response = $this->actingAs($this->admin)->get(route('quotes.pdf', $quote));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_customer_can_place_order_from_cart(): void
    {
        $payload = [
            'customer_name' => 'Maria Lopez',
            'customer_document' => '45896321',
            'customer_document_type' => 'DNI',
            'customer_phone' => '956123456',
            'delivery_mode' => 'Delivery Local Moyobamba 🛵',
            'delivery_address' => 'Jr. 25 de Mayo 320',
            'payment_method' => 'Yape 📱',
            'notes' => 'Entregar por la tarde',
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'product_name' => $this->product->name,
                    'quantity' => 1,
                    'unit_price' => 3500.00,
                ],
            ],
        ];

        $response = $this->postJson(route('orders.store'), $payload);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'order_id',
            'order_number',
            'ticket_url',
        ]);

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Maria Lopez',
            'delivery_mode' => 'Delivery Local Moyobamba 🛵',
            'total' => 3500.00,
        ]);
    }

    public function test_customer_can_download_order_ticket_pdf(): void
    {
        $order = Order::create([
            'order_number' => Order::generateNextNumber(),
            'customer_name' => 'Maria Lopez',
            'customer_document' => '45896321',
            'customer_document_type' => 'DNI',
            'customer_phone' => '956123456',
            'delivery_mode' => 'Delivery Local Moyobamba 🛵',
            'payment_method' => 'Yape 📱',
            'subtotal' => 3500.00,
            'total' => 3500.00,
            'status' => 'recibido',
        ]);

        $order->items()->create([
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'quantity' => 1,
            'unit_price' => 3500.00,
            'subtotal' => 3500.00,
        ]);

        $response = $this->get(route('orders.ticket', $order));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }
}
