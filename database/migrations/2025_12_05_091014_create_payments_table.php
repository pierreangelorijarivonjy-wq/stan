<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // inscription, réinscription, examen
            $table->string('provider');           // mvola ou orange
            $table->string('phone');
            $table->decimal('amount', 12, 0);
            $table->string('transaction_id')->unique();
            $table->string('status')->default('pending'); // pending, paid, failed
            $table->string('method')->nullable(); // mobile_money, card, bank_transfer
            $table->string('receipt_url')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
