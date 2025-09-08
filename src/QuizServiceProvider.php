<?php

namespace admin\quizzes;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class QuizServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes, views, migrations from the package  
        $this->loadViewsFrom([
            base_path('Modules/Quizzes/resources/views'), // Published module views first
            resource_path('views/admin/quiz'), // Published views second
            __DIR__ . '/../resources/views'      // Package views as fallback
        ], 'quiz');

        // Load published module config first (if it exists), then fallback to package config
        if (file_exists(base_path('Modules/Quizzes/config/quiz.php'))) {
            $this->mergeConfigFrom(base_path('Modules/Quizzes/config/quiz.php'), 'quiz.constants');
        } else {
            // Fallback to package config if published config doesn't exist
            $this->mergeConfigFrom(__DIR__ . '/../config/quiz.php', 'quiz.constants');
        }

        // Also register module views with a specific namespace for explicit usage
        if (is_dir(base_path('Modules/Quizzes/resources/views'))) {
            $this->loadViewsFrom(base_path('Modules/Quizzes/resources/views'), 'quizzes-module');
        }
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        // Also load migrations from published module if they exist
        if (is_dir(base_path('Modules/Quizzes/database/migrations'))) {
            $this->loadMigrationsFrom(base_path('Modules/Quizzes/database/migrations'));
        }

        // Only publish automatically during package installation, not on every request
        // Use 'php artisan quizzes:publish' command for manual publishing
        // $this->publishWithNamespaceTransformation();

        // Standard publishing for non-PHP files
        $this->publishes([
            __DIR__ . '/../config/' => base_path('Modules/Quizzes/config/'),
            __DIR__ . '/../database/migrations' => base_path('Modules/Quizzes/database/migrations'),
            __DIR__ . '/../resources/views' => base_path('Modules/Quizzes/resources/views/'),
        ], 'quiz');

        $this->registerAdminRoutes();
    }

    protected function registerAdminRoutes()
    {
        if (!Schema::hasTable('admins')) {
            return; // Avoid errors before migration
        }

        $admin = DB::table('admins')
            ->orderBy('created_at', 'asc')
            ->first();

        $slug = $admin->website_slug ?? 'admin';

        Route::middleware('web')
            ->prefix("{$slug}/admin") // dynamic prefix
            ->group(function () {
                // Load routes from published module first, then fallback to package
                if (file_exists(base_path('Modules/Quizzes/routes/web.php'))) {
                    $this->loadRoutesFrom(base_path('Modules/Quizzes/routes/web.php'));
                } else {
                    $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
                }
            });
    }

    public function register()
    {
        // Register the publish command
        if ($this->app->runningInConsole()) {
            $this->commands([
                \admin\quizzes\Console\Commands\PublishQuizzesModuleCommand::class,
                \admin\quizzes\Console\Commands\CheckModuleStatusCommand::class,
                \admin\quizzes\Console\Commands\DebugQuizzesCommand::class,
            ]);
        }
    }

    /**
     * Publish files with namespace transformation
     */
    protected function publishWithNamespaceTransformation()
    {
        // Define the files that need namespace transformation
        $filesWithNamespaces = [
            // Controllers
            __DIR__ . '/../src/Controllers/QuizAnswerController.php' => base_path('Modules/Quizzes/app/Http/Controllers/Admin/QuizAnswerController.php'),
           __DIR__ . '/../src/Controllers/QuizManagerController.php' => base_path('Modules/Quizzes/app/Http/Controllers/Admin/QuizManagerController.php'),
           __DIR__ . '/../src/Controllers/QuizQuestionController.php' => base_path('Modules/Quizzes/app/Http/Controllers/Admin/QuizQuestionController.php'),

            // Models
            __DIR__ . '/../src/Models/Quiz.php' => base_path('Modules/Quizzes/app/Models/Quiz.php'),
            __DIR__ . '/../src/Models/QuizAnswer.php' => base_path('Modules/Quizzes/app/Models/QuizAnswer.php'),
            __DIR__ . '/../src/Models/QuizQuestion.php' => base_path('Modules/Quizzes/app/Models/QuizQuestion.php'),


            // Requests
            __DIR__ . '/../src/Requests/QuizAnswerRequest.php' => base_path('Modules/Quizzes/app/Http/Requests/QuizAnswerRequest.php'),
            __DIR__ . '/../src/Requests/QuizCreateRequest.php' => base_path('Modules/Quizzes/app/Http/Requests/QuizCreateRequest.php'),
            __DIR__ . '/../src/Requests/QuizQuestionRequest.php' => base_path('Modules/Quizzes/app/Http/Requests/QuizQuestionRequest.php'),
            __DIR__ . '/../src/Requests/QuizUpdateRequest.php' => base_path('Modules/Quizzes/app/Http/Requests/QuizUpdateRequest.php'),

            // Routes
            __DIR__ . '/routes/web.php' => base_path('Modules/Quizzes/routes/web.php'),
        ];

        foreach ($filesWithNamespaces as $source => $destination) {
            if (File::exists($source)) {
                // Create destination directory if it doesn't exist
                File::ensureDirectoryExists(dirname($destination));

                // Read the source file
                $content = File::get($source);

                // Transform namespaces based on file type
                $content = $this->transformNamespaces($content, $source);

                // Write the transformed content to destination
                File::put($destination, $content);
            }
        }
    }

    /**
     * Transform namespaces in PHP files
     */
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
            $content = $this->transformControllerNamespaces($content);
        } elseif (str_contains($sourceFile, 'Models')) {
            $content = $this->transformModelNamespaces($content);
        } elseif (str_contains($sourceFile, 'Requests')) {
            $content = $this->transformRequestNamespaces($content);
        } elseif (str_contains($sourceFile, 'routes')) {
            $content = $this->transformRouteNamespaces($content);
        }

        return $content;
    }

    /**
     * Transform controller-specific namespaces
     */
    protected function transformControllerNamespaces($content)
    {
        // Update use statements for models and requests
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
            'use admin\\courses\\Models\\Course;',
            'use Modules\\Courses\\app\\Models\\Course;',
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

        return $content;
    }

    /**
     * Transform model-specific namespaces
     */
    protected function transformModelNamespaces($content)
    {
        // Any model-specific transformations
        $content = str_replace(
            'use admin\\courses\\Models\\Course;',
            'use Modules\\Courses\\app\\Models\\Course;',
            $content
        );
        return $content;
    }

    /**
     * Transform request-specific namespaces
     */
    protected function transformRequestNamespaces($content)
    {
        // Any request-specific transformations
        return $content;
    }

    /**
     * Transform route-specific namespaces
     */
    protected function transformRouteNamespaces($content)
    {
         // Update controller references in routes
        $content = str_replace(
            'admin\\quizzes\\Controllers\\QuizAnswerController',
            'Modules\\Quizzes\\app\\Http\\Controllers\\Admin\\QuizAnswerController',
            $content
        );
        $content = str_replace(
            'admin\\quizzes\\Controllers\\QuizManagerController',
            'Modules\\Quizzes\\app\\Http\\Controllers\\Admin\\QuizManagerController',
            $content
        );
        $content = str_replace(
            'admin\\quizzes\\Controllers\\QuizQuestionController',
            'Modules\\Quizzes\\app\\Http\\Controllers\\Admin\\QuizQuestionController',
            $content
        );

        return $content;
    }
}