<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 10)->unique();
            $table->foreignId('location_id')->constrained();
            $table->enum('service_type', ['sur_place', 'a_emporter', 'livraison']);
            $table->enum('status', ['received', 'confirmed', 'preparing', 'ready', 'delivered', 'cancelled'])->default('received');
            $table->string('customer_name');
            $table->string('phone')->nullable();
            $table->string('table_number')->nullable();
            $table->string('address')->nullable();
            $table->text('note')->nullable();
            $table->string('promo_code')->nullable();
            $table->unsignedInteger('total');
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('variant_name')->nullable();
            $table->unsignedInteger('price');
            $table->unsignedInteger('quantity')->default(1);
            $table->json('removed_ingredients')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
