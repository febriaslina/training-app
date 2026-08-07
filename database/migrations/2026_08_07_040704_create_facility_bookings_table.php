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
        Schema::create('facility_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id')->constrained('facilities')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->string('event_name');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('guest_count');
            $table->decimal('total_cost', 15, 2);
            $table->string('status')->default('pending'); // pending, confirmed, ongoing, completed, cancelled
            $table->boolean('arrival_confirmed')->default(false);
            $table->decimal('cancellation_fee', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['facility_id', 'start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facility_bookings');
    }
};
