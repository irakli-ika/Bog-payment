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
            $table->string('transaction_id')->unique()->nullable();
            $table->foreignId('user_id');
            $table->foreignId('card_id')->nullable();
            $table->string('status')->default('pending'); // remove in mysql
            // $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->unsignedInteger('amount');
            $table->string('currency')->default('gel'); // remove in mysql
            // $table->enum('currency', ['gel', 'usd'])->default('gel');
            $table->string('payment_method'); // remove in mysql
            // $table->enum('payment_method', ['bog', 'tbc', 'mbank']);
            $table->string('type')->default('normal'); // remove in mysql
            // $table->enum('type', ['normal', 'saveCard', 'chargeCard', 'subscribe', 'chargeSubscription']);
            // // $table->boolean('save_card')->default(0);
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
