<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code', 30)->unique();
            $table->foreignId('user_id')->constrained()->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('court_id')->constrained()->restrictOnDelete()->cascadeOnUpdate();
            $table->date('booking_date')->index();
            $table->decimal('total_amount', 12, 2);
            $table->enum('status', ['pending', 'confirmed', 'in_use', 'completed', 'cancelled', 'expired'])->default('pending');
            $table->enum('payment_status', ['unpaid', 'pending_verification', 'paid', 'refunded'])->default('unpaid');
            $table->timestamp('expired_at');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['court_id', 'booking_date', 'status'], 'idx_bookings_availability');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
