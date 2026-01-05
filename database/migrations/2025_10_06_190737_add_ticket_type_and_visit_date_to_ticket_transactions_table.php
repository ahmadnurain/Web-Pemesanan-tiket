<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ticket_transactions', function (Blueprint $table) {
            // letakkan setelah kolom total_tickets (ubah posisi jika perlu)
            $table->string('ticket_type', 50)
                ->default('regular')
                ->after('total_tickets');

            $table->date('visit_date')
                ->nullable()
                ->after('ticket_type');

            // index untuk filter/urutkan berdasarkan tanggal kunjungan
            $table->index('visit_date', 'ticket_transactions_visit_date_index');
        });
    }

    public function down(): void
    {
        Schema::table('ticket_transactions', function (Blueprint $table) {
            // hapus index dulu baru kolom
            $table->dropIndex('ticket_transactions_visit_date_index');
            $table->dropColumn(['ticket_type', 'visit_date']);
        });
    }
};