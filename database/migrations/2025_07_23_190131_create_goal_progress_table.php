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
        Schema::create('goal_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('financial_goal_id')->constrained()->onDelete('cascade');
            $table->decimal('current_amount', 15, 2);
            $table->integer('month'); // 1-12
            $table->integer('year');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['financial_goal_id', 'month', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goal_progress');
    }
};
