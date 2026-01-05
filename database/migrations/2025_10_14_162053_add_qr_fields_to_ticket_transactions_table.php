<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ticket_transactions', function (Blueprint $t) {
            $t->string('qr_secret', 64)->nullable()->after('uuid');
            $t->unsignedInteger('scan_count')->default(0)->after('ticket_status');
            $t->timestamp('last_scanned_at')->nullable()->after('scan_count');
            $t->timestamp('used_at')->nullable()->after('last_scanned_at');
            $t->foreignId('scanned_by')->nullable()->constrained('users')->nullOnDelete()->after('used_at');
        });
    }
    public function down(): void
    {
        Schema::table('ticket_transactions', function (Blueprint $t) {
            $t->dropConstrainedForeignId('scanned_by');
            $t->dropColumn(['qr_secret', 'scan_count', 'last_scanned_at', 'used_at']);
        });
    }
};
