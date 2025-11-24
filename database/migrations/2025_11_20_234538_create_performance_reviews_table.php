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
        Schema::create('performance_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reviewer_id')->constrained('users');
            $table->string('review_period');
            $table->integer('quality_of_work')->comment('Rating 1-10');
            $table->integer('productivity')->comment('Rating 1-10');
            $table->integer('communication')->comment('Rating 1-10');
            $table->integer('teamwork')->comment('Rating 1-10');
            $table->integer('leadership')->comment('Rating 1-10');
            $table->decimal('overall_rating', 3, 2);
            $table->text('strengths')->nullable();
            $table->text('area_for_improvement')->nullable();
            $table->text('goals')->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_reviews');
    }
};
