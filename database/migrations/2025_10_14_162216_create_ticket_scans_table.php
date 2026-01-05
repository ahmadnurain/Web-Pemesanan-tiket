<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ticket_scans', function (Blueprint $t) {
            $t->id();
            $t->foreignId('ticket_transaction_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->string('result', 32); // valid, already_used, invalid_sig, not_found, unauthorized, error
            $t->string('ip', 64)->nullable();
            $t->string('user_agent', 255)->nullable();
            $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('ticket_scans');
    }
};
