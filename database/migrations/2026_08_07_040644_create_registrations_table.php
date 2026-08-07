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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->string('registration_code')->unique();
            $table->foreignId('training_id')->constrained('trainings')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('verified_by')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('status')->default('pending'); // pending, confirmed, rejected, cancelled
            $table->string('graduation_status')->default('not_assessed'); // not_assessed, passed, failed
            $table->text('notes')->nullable();
            $table->text('operator_notes')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->string('confirmed_via')->nullable(); // system, whatsapp, email, phone
            $table->jsonb('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['training_id', 'customer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
