<?php

namespace admin\quizzes\Console\Commands;

use Illuminate\Console\Command;

class PublishQuizzesModuleCommand extends Command
{
    protected $signature = 'quizzes:publish {--force : Overwrite any existing files}';
    protected $description = 'Publish Quizzes module assets (migrations, views) to the application Modules folder';

    public function handle(): int
    {
        $this->call('vendor:publish', [
            '--provider' => 'admin\\quizzes\\QuizServiceProvider',
            '--tag' => 'quiz',
            '--force' => (bool)$this->option('force'),
        ]);

        $this->info('Quizzes module assets published.');
        return self::SUCCESS;
    }
}

