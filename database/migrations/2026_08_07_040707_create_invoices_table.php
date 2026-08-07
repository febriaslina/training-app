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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->nullable()->constrained('registrations')->cascadeOnDelete();
            $table->foreignId('facility_booking_id')->nullable()->constrained('facility_bookings')->cascadeOnDelete();
            $table->string('invoice_number')->unique();
            $table->decimal('total_amount', 15, 2);
            $table->string('status')->default('draft'); // draft, sent, paid, settled, cancelled
            $table->date('due_date');
            $table->timestamp('paid_at')->nullable();
            $table->jsonb('line_items')->nullable();
            $table->text('notes')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
