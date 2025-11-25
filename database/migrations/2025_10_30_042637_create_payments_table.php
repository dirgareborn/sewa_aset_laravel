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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')
                ->constrained('bookings')
                ->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->enum('method', ['QRIS', 'VA'])->default('QRIS');
            $table->enum('status', ['pending', 'confirmed', 'failed', 'refunded'])->default('pending');
            $table->dateTime('paid_at')->nullable();
            $table->string('proof')->nullable(); 
            $table->timestamp('proof_uploaded_at')->nullable();
            $table->string('uploaded_by')->nullable();
            $table->json('proof_meta')->nullable();
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
