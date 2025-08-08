<?php

namespace admin\quizzes\Console\Commands;

use Illuminate\Console\Command;

class DebugQuizzesCommand extends Command
{
    protected $signature = 'quizzes:debug';
    protected $description = 'Debug Quizzes module configuration and view resolution';

    public function handle(): int
    {
        $this->line('Config quiz.constants:');
        $constants = config('quiz.constants');
        $this->line(json_encode($constants));

        $this->line('Attempting to resolve a sample view: quiz::admin.question.index');
        try {
            view('quiz::admin.question.index');
            $this->info('View resolved successfully (if template exists).');
        } catch (\Throwable $e) {
            $this->error('Failed to resolve view: ' . $e->getMessage());
        }
        return self::SUCCESS;
    }
}

