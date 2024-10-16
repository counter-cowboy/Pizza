<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cart_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')
                ->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')
                ->nullable()->constrained()->nullOnDelete();
            $table->unique(['cart_id', 'product_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_product');
    }
};