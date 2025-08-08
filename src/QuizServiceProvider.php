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

        $this->mergeConfigFrom(__DIR__.'/../config/quiz.php', 'quiz.constants');
        
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
        // Use a manual publish command if needed later
        // $this->publishWithNamespaceTransformation();
        
        // Standard publishing for non-PHP files
        $this->publishes([
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
                $this->loadRoutesFrom(__DIR__.'/routes/web.php');
            });
    }

    public function register()
    {
        // Register console commands similar to FAQ module
        if ($this->app->runningInConsole()) {
            $this->commands([
                \admin\quizzes\Console\Commands\PublishQuizzesModuleCommand::class,
                \admin\quizzes\Console\Commands\CheckModuleStatusCommand::class,
                \admin\quizzes\Console\Commands\DebugQuizzesCommand::class,
                \admin\quizzes\Console\Commands\TestViewResolutionCommand::class,
            ]);
        }
    }

    /**
     * Optional: publish with namespace transformation (kept for parity with FAQs)
     */
    protected function publishWithNamespaceTransformation()
    {
        $filesWithNamespaces = [
            // __DIR__ . '/../src/Controllers/QuizManagerController.php' => base_path('Modules/Quizzes/app/Http/Controllers/Admin/QuizManagerController.php'),
            // __DIR__ . '/../src/Models/Quiz.php' => base_path('Modules/Quizzes/app/Models/Quiz.php'),
            // __DIR__ . '/routes/web.php' => base_path('Modules/Quizzes/routes/web.php'),
        ];

        foreach ($filesWithNamespaces as $source => $destination) {
            if (File::exists($source)) {
                File::ensureDirectoryExists(dirname($destination));
                $content = File::get($source);
                $content = $this->transformNamespaces($content, $source);
                File::put($destination, $content);
            }
        }
    }

    protected function transformNamespaces($content, $sourceFile)
    {
        $namespaceTransforms = [
            'namespace admin\\quizzes\\Controllers;' => 'namespace Modules\\Quizzes\\app\\Http\\Controllers\\Admin;',
            'namespace admin\\quizzes\\Models;' => 'namespace Modules\\Quizzes\\app\\Models;',
            'namespace admin\\quizzes\\Requests;' => 'namespace Modules\\Quizzes\\app\\Http\\Requests;',
            'use admin\\quizzes\\Controllers\\' => 'use Modules\\Quizzes\\app\\Http\\Controllers\\Admin\\',
            'use admin\\quizzes\\Models\\' => 'use Modules\\Quizzes\\app\\Models\\',
            'use admin\\quizzes\\Requests\\' => 'use Modules\\Quizzes\\app\\Http\\Requests\\',
        ];

        foreach ($namespaceTransforms as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        return $content;
    }
}
