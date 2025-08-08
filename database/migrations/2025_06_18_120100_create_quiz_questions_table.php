<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quiz_id')->index();
            $table->text('question_text');
            $table->enum('question_type', ['multiple_choice', 'true_false', 'fill_in_blank', 'text']);
            $table->text('explanation')->nullable();
            $table->unsignedSmallInteger('points')->default(1)->comment('Points/marks for the question');
            $table->timestamps();

            //foreign key
            $table->foreign('quiz_id')->references('id')->on('quizzes')->onDelete('cascade');            
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};

