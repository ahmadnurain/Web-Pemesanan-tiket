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
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_transaction_id')->constrained('ticket_transactions')->onDelete('cascade');
            // We use nullable here just in case a ticket type is deleted later, we still have the history in snapshots
            $table->foreignId('destination_ticket_type_id')->nullable()->constrained('destination_ticket_types')->nullOnDelete();

            $table->string('name'); // Snapshot of ticket name at time of purchase
            $table->decimal('price_per_unit', 10, 2); // Snapshot of price
            $table->integer('quantity');
            $table->decimal('total_price', 10, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};
