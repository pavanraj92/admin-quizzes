<?php

namespace admin\quizzes\Console\Commands;

use Illuminate\Console\Command;

class CheckModuleStatusCommand extends Command
{
    protected $signature = 'quizzes:status';
    protected $description = 'Check Quizzes module status (views, migrations paths)';

    public function handle(): int
    {
        $paths = [
            'views (package)' => __DIR__ . '/../../../resources/views',
            'views (module)' => base_path('Modules/Quizzes/resources/views'),
            'migrations (package)' => __DIR__ . '/../../../database/migrations',
            'migrations (module)' => base_path('Modules/Quizzes/database/migrations'),
        ];

        foreach ($paths as $label => $path) {
            $this->line(sprintf('%-28s: %s %s', $label, $path, is_dir($path) ? '' : '(missing)'));
        }

        return self::SUCCESS;
    }
}

