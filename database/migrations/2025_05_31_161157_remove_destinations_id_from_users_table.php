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
        if (! Schema::hasColumn('users', 'destinations_id')) {
            return; // sudah pernah dihapus, skip
        }
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['destinations_id']);
            $table->dropColumn('destinations_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('destinations_id')->nullable()->constrained('destinations')->onDelete('cascade');
        });
    }
};
