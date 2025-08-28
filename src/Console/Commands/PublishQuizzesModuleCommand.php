<?php

namespace admin\quizzes\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishQuizzesModuleCommand extends Command
{
    protected $signature = 'quizzes:publish {--force : Overwrite any existing files}';
    protected $description = 'Publish Quizzes module assets (migrations, views) to the application Modules folder';

    public function handle()
    {
        $this->info('Publishing Quizzes module files...');

        // Check if module directory exists
        $moduleDir = base_path('Modules/Quizzes');
        if (!File::exists($moduleDir)) {
            File::makeDirectory($moduleDir, 0755, true);
        }

        // Publish with namespace transformation
        $this->publishWithNamespaceTransformation();
        
        // Publish other files
        $this->call('vendor:publish', [
            '--tag' => 'quiz',
            '--force' => $this->option('force')
        ]);

        // Update composer autoload
        $this->updateComposerAutoload();

        $this->info('Quizzes module published successfully!');
        $this->info('Please run: composer dump-autoload');
    }

    protected function publishWithNamespaceTransformation()
    {
        $basePath = dirname(dirname(__DIR__)); // Go up to packages/admin/quizzes/src

        $filesWithNamespaces = [
            // Controllers
            $basePath . '/../src/Controllers/QuizAnswerController.php' => base_path('Modules/Quizzes/app/Http/Controllers/Admin/QuizAnswerController.php'),
            $basePath . '/../src/Controllers/QuizManagerController.php' => base_path('Modules/Quizzes/app/Http/Controllers/Admin/QuizManagerController.php'),
            $basePath . '/../src/Controllers/QuizQuestionController.php' => base_path('Modules/Quizzes/app/Http/Controllers/Admin/QuizQuestionController.php'),

            // Models
            $basePath . '/../src/Models/Quiz.php' => base_path('Modules/Quizzes/app/Models/Quiz.php'),
            $basePath . '/../src/Models/QuizAnswer.php' => base_path('Modules/Quizzes/app/Models/QuizAnswer.php'),
            $basePath . '/../src/Models/QuizQuestion.php' => base_path('Modules/Quizzes/app/Models/QuizQuestion.php'),

            // Requests
            $basePath . '/../src/Requests/QuizAnswerRequest.php' => base_path('Modules/Quizzes/app/Http/Requests/QuizAnswerRequest.php'),
            $basePath . '/../src/Requests/QuizCreateRequest.php' => base_path('Modules/Quizzes/app/Http/Requests/QuizCreateRequest.php'),
            $basePath . '/../src/Requests/QuizQuestionRequest.php' => base_path('Modules/Quizzes/app/Http/Requests/QuizQuestionRequest.php'),
            $basePath . '/../src/Requests/QuizUpdateRequest.php' => base_path('Modules/Quizzes/app/Http/Requests/QuizUpdateRequest.php'),


            // Routes
           $basePath . '/routes/web.php' => base_path('Modules/Quizzes/routes/web.php'),
        ];

        foreach ($filesWithNamespaces as $source => $destination) {
            if (File::exists($source)) {
                File::ensureDirectoryExists(dirname($destination));
                
                $content = File::get($source);
                $content = $this->transformNamespaces($content, $source);
                
                File::put($destination, $content);
                $this->info("Published: " . basename($destination));
            } else {
                $this->warn("Source file not found: " . $source);
            }
        }
    }

    protected function transformNamespaces($content, $sourceFile)
    {
        // Define namespace mappings
        $namespaceTransforms = [
            // Main namespace transformations
            'namespace admin\\quizzes\\Controllers;' => 'namespace Modules\\Quizzes\\app\\Http\\Controllers\\Admin;',
            'namespace admin\\quizzes\\Models;' => 'namespace Modules\\Quizzes\\app\\Models;',
            'namespace admin\\quizzes\\Requests;' => 'namespace Modules\\Quizzes\\app\\Http\\Requests;',

            // Use statements transformations
            'use admin\\quizzes\\Controllers\\' => 'use Modules\\Quizzes\\app\\Http\\Controllers\\Admin\\',
            'use admin\\quizzes\\Models\\' => 'use Modules\\Quizzes\\app\\Models\\',
            'use admin\\quizzes\\Requests\\' => 'use Modules\\Quizzes\\app\\Http\\Requests\\',

            // Class references in routes
            'admin\\quizzes\\Controllers\\QuizAnswerController' => 'Modules\\Quizzes\\app\\Http\\Controllers\\Admin\\QuizAnswerController',
            'admin\\quizzes\\Controllers\\QuizManagerController' => 'Modules\\Quizzes\\app\\Http\\Controllers\\Admin\\QuizManagerController',
            'admin\\quizzes\\Controllers\\QuizQuestionController' => 'Modules\\Quizzes\\app\\Http\\Controllers\\Admin\\QuizQuestionController',
        ];

        // Apply transformations
        foreach ($namespaceTransforms as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        // Handle specific file types
        if (str_contains($sourceFile, 'Controllers')) {
            $content = str_replace(
                'use admin\\quizzes\\Models\\Quiz;',
                'use Modules\\Quizzes\\app\\Models\\Quiz;',
                $content
            );
            $content = str_replace(
                'use admin\\quizzes\\Models\\QuizAnswer;',
                'use Modules\\Quizzes\\app\\Models\\QuizAnswer;',
                $content
            );
            $content = str_replace(
                'use admin\\quizzes\\Models\\QuizQuestion;',
                'use Modules\\Quizzes\\app\\Models\\QuizQuestion;',
                $content
            );
           
            $content = str_replace(
                'use admin\\quizzes\\Requests\\QuizAnswerRequest;',
                'use Modules\\Quizzes\\app\\Http\\Requests\\QuizAnswerRequest;',
                $content
            );

            $content = str_replace(
                'use admin\\quizzes\\Requests\\QuizCreateRequest;',
                'use Modules\\Quizzes\\app\\Http\\Requests\\QuizCreateRequest;',
                $content
            );
            $content = str_replace(
                'use admin\\quizzes\\Requests\\QuizQuestionRequest;',
                'use Modules\\Quizzes\\app\\Http\\Requests\\QuizQuestionRequest;',
                $content
            );
            $content = str_replace(
                'use admin\\quizzes\\Requests\\QuizUpdateRequest;',
                'use Modules\\Quizzes\\app\\Http\\Requests\\QuizUpdateRequest;',
                $content
            );
        }

        return $content;
    }

    protected function updateComposerAutoload()
    {
        $composerFile = base_path('composer.json');
        $composer = json_decode(File::get($composerFile), true);

        // Add module namespace to autoload
        if (!isset($composer['autoload']['psr-4']['Modules\\Quizzes\\'])) {
            $composer['autoload']['psr-4']['Modules\\Quizzes\\'] = 'Modules/Quizzes/app/';

            File::put($composerFile, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            $this->info('Updated composer.json autoload');
        }
    }
}

