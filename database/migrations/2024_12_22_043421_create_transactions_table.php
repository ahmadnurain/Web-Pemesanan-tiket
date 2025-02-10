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
        Schema::create('ticket_transactions', function (Blueprint $table) {
            $table->id(); // Primary key
            // $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relasi ke tabel users
            $table->foreignId('destination_id')->constrained('destinations')->onDelete('cascade'); // Relasi ke tabel destinations

            // Informasi Transaksi
            $table->string('name'); // Nama pembeli
            $table->string('email'); // Email pembeli
            $table->string('phone_number'); // Nomor HP pembeli

            // Informasi Ticket
            $table->string('ticket_code')->unique(); // Kode tiket unik
            $table->enum('ticket_status', ['unused', 'used'])->default('unused'); // Status tiket

            // Informasi Pembayaran
            $table->decimal('amount', 10, 2); // Total pembayaran
            $table->enum('payment_status', ['pending', 'succeeded'])->default('pending'); // Status pembayaran
            $table->string('payment_type')->nullable();
            $table->integer('total_tickets'); // Jumlah tiket

            $table->timestamps(); // Waktu transaksi (created_at & updated_at)
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