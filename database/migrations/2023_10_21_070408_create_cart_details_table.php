<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cart_details', function (Blueprint $table) {
            $table->id();
            $table->integer('cart_id');
            $table->integer('product_id');
            $table->decimal('price');
            $table->integer('quantity');
            $table->decimal('sub_total')->nullable();
            $table->decimal('ship_fee')->nullable();
            $table->decimal('total_price')->nullable();
            $table->integer('user_address_id')->nullable();
            $table->string('cancel_reason')->nullable();
            $table->timestamp('cancel_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_details');
    }
};
