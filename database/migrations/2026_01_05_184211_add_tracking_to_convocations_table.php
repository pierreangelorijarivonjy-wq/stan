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
        Schema::table('convocations', function (Blueprint $table) {
            $table->timestamp('scanned_at')->nullable();
            $table->foreignId('scanned_by')->nullable()->constrained('users');
            $table->timestamp('deactivated_at')->nullable();
            $table->string('deactivation_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('convocations', function (Blueprint $table) {
            $table->dropForeign(['scanned_by']);
            $table->dropColumn(['scanned_at', 'scanned_by', 'deactivated_at', 'deactivation_reason']);
        });
    }
};
