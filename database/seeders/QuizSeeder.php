<?php

namespace Admin\Quizzes\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $quizzes = [
            [
                'user_id'       => 1,
                'course_id'     => 1,
                'title'         => 'Introduction to PHP',
                'description'   => 'Basic quiz covering PHP fundamentals.',
                'difficulty'    => 'easy',
                'passing_score' => 60,
                'time_limit'    => 30, // minutes
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'user_id'       => 1,
                'course_id'     => 1,
                'title'         => 'Laravel Basics',
                'description'   => 'Covers routes, controllers, and migrations.',
                'difficulty'    => 'medium',
                'passing_score' => 70,
                'time_limit'    => 45,
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'user_id'       => 1,
                'course_id'     => 1,
                'title'         => 'Advanced Eloquent',
                'description'   => 'Test your knowledge on Laravel Eloquent ORM.',
                'difficulty'    => 'hard',
                'passing_score' => 80,
                'time_limit'    => 60,
                'status'        => 'draft',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
        ];

        foreach ($quizzes as $quiz) {
            DB::table('quizzes')->updateOrInsert(
                ['title' => $quiz['title']], // prevent duplicates
                $quiz
            );
        }
    }
}
