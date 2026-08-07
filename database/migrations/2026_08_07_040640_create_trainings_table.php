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
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // technical, managerial, functional
            $table->text('description')->nullable();
            $table->integer('duration_days');
            $table->text('requirements')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('location');
            $table->integer('max_quota')->default(50);
            $table->integer('filled_quota')->default(0);
            $table->string('status')->default('draft'); // draft, open, full, ongoing, completed, cancelled
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('trainings');
    }
};
