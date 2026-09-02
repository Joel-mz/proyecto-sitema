<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cotizaciones Internas (Panel de Administración)
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->string('quote_number')->unique(); // e.g. COT-2026-0001
            $table->string('customer_name');
            $table->string('customer_document')->nullable(); // DNI o RUC
            $table->string('customer_document_type')->default('DNI'); // DNI, RUC
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_address')->nullable();
            $table->string('city')->nullable()->default('Moyobamba');
            $table->integer('validity_days')->default(15);
            $table->enum('status', ['pendiente', 'aprobada', 'rechazada', 'facturada'])->default('pendiente');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('quote_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_name');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->timestamps();
        });

        // Pedidos del Carrito Público (Tickets de Compra)
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // e.g. PED-2026-0001
            $table->string('customer_name');
            $table->string('customer_document'); // DNI o RUC
            $table->string('customer_document_type')->default('DNI'); // DNI, RUC
            $table->string('customer_phone');
            $table->string('delivery_mode'); // Recojo en tienda, Delivery Moyobamba, Envio San Martin, Todo el Peru
            $table->string('delivery_address')->nullable();
            $table->string('payment_method'); // Yape, Plin, Transferencia, Contraentrega
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->enum('status', ['recibido', 'en_proceso', 'entregado', 'cancelado'])->default('recibido');
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_name');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('quote_items');
        Schema::dropIfExists('quotes');
    }
};
