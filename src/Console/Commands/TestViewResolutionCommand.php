<?php

namespace admin\quizzes\Console\Commands;

use Illuminate\Console\Command;

class TestViewResolutionCommand extends Command
{
    protected $signature = 'quizzes:test-views';
    protected $description = 'Test resolving key Quizzes module views';

    public function handle(): int
    {
        $views = [
            'quiz::admin.question.index',
            'quiz::admin.answer.index',
        ];

        foreach ($views as $view) {
            try {
                view($view);
                $this->info("Resolved: {$view}");
            } catch (\Throwable $e) {
                $this->error("Failed: {$view} -> {$e->getMessage()}");
            }
        }

        return self::SUCCESS;
    }
}

