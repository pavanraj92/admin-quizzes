<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();            
            $table->unsignedBigInteger('user_id')->nullable()->index();            
            $table->unsignedBigInteger('course_id')->nullable()->index();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->nullable();
            $table->unsignedTinyInteger('passing_score')->default(0); // percentage 0-100
            $table->decimal('time_limit', 5, 2)->nullable(); // minutes
            $table->enum('status', ['active', 'inactive', 'draft'])->default('draft');
            $table->timestamps();
            $table->softDeletes();

            //foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');           
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};

