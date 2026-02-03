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
        Schema::create('bank_statements', function (Blueprint $table) {
            $table->id();
            $table->string('source'); // mvola, orange, bni, etc.
            $table->date('date');
            $table->string('reference');
            $table->decimal('amount', 12, 0);
            $table->json('raw_data')->nullable();
            $table->string('file_path')->nullable();
            $table->enum('status', ['pending', 'matched', 'exception'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_statements');
    }
};
