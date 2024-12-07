<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()->cascadeOnDelete();
            $table->unsignedDecimal('total_amount')->nullable();
            $table->enum('status', ['in_progress', 'delivering', 'delivered', 'canceled']);
            $table->string('address');
            $table->string('phone');
            $table->string('email');
            $table->dateTime('delivery_time');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
