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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // $table->enum('provider', 'bog')
            $table->string('provider')->default('bog'); // remove in mysql
            $table->string('parent_order_id')->unique();
            $table->string('number');
            $table->string('expiry_date');
            $table->string('type'); // remove in mysql
            // $table->enum('type', ['amex', 'mc', 'visa']);
            // $table->enum('status', ['pending', 'active', 'active', 'disabled'])->default('pending');
            $table->string('status')->default('pending'); // remove in mysql
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
