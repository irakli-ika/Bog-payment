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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('idempotency_key')->unique();
            $table->foreignId('user_id');
            $table->string('order_id')->nullable();
            $table->string('status'); // remove in mysql
            // $table->enum('status', ['pending', 'paid', 'failed', 'refunded']);
            $table->unsignedInteger('amount');
            $table->string('currency'); // remove in mysql
            // $table->enum('currency', ['gel', 'usd']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
