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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('idempotency_key')->unique();
            $table->string('transaction_id')->nullable();
            $table->foreignId('user_id');
            $table->foreignId('card_id')->nullable();
            $table->string('status')->default('pending');
            // $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled'])->default('pending'); // remove in mysql
            $table->unsignedInteger('amount');
            $table->string('currency')->default('gel');
            // $table->enum('currency', ['gel', 'usd'])->default('gel'); // remove in mysql
            $table->string('payment_method');
            $table->boolean('save_card')->default(0);
            // $table->enum('payment_method', ['bog', 'tbc', 'mbank']); // remove in mysql
            $table->json('log')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
