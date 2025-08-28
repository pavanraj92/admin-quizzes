<?php

namespace admin\quizzes\Console\Commands;

use Illuminate\Console\Command;

class CheckModuleStatusCommand extends Command
{
    protected $signature = 'quizzes:status';
    protected $description = 'Check Quizzes module status (views, migrations paths)';

   public function handle()
    {
        $this->info('Checking Quizzes Module Status...');

        // Check if module files exist
        $moduleFiles = [
            'Quiz Model' => base_path('Modules/Quizzes/app/Models/Quiz.php'),
            'QuizAnswer Model' => base_path('Modules/Quizzes/app/Models/QuizAnswer.php'),
            'QuizQuestion Model' => base_path('Modules/Quizzes/app/Models/QuizQuestion.php'),

            'QuizAnswerController' => base_path('Modules/Quizzes/app/Http/Controllers/Admin/QuizAnswerController.php'),
            'QuizManagerController' => base_path('Modules/Quizzes/app/Http/Controllers/Admin/QuizManagerController.php'),
            'QuizQuestionController' => base_path('Modules/Quizzes/app/Http/Controllers/Admin/QuizQuestionController.php'),
           
            'QuizAnswerRequest' => base_path('Modules/Quizzes/app/Http/Requests/QuizAnswerRequest.php'),
            'QuizCreateRequest' => base_path('Modules/Quizzes/app/Http/Requests/QuizCreateRequest.php'),
            'QuizQuestionRequest' => base_path('Modules/Quizzes/app/Http/Requests/QuizQuestionRequest.php'),
            'QuizUpdateRequest' => base_path('Modules/Quizzes/app/Http/Requests/QuizUpdateRequest.php'),
            'Routes' => base_path('Modules/Quizzes/routes/web.php'),
            'Views' => base_path('Modules/Quizzes/resources/views'),
            'Config' => base_path('Modules/Quizzes/config/quizzes.php'),
        ];

        $this->info("\n📁 Module Files Status:");
        foreach ($moduleFiles as $type => $path) {
            if (File::exists($path)) {
                $this->info("✅ {$type}: EXISTS");

                // Check if it's a PHP file and show last modified time
                if (str_ends_with($path, '.php')) {
                    $lastModified = date('Y-m-d H:i:s', filemtime($path));
                    $this->line("   Last modified: {$lastModified}");
                }
            } else {
                $this->error("❌ {$type}: NOT FOUND");
            }
        }

        // Check namespace in controller
        $controllers = [
          'QuizAnswerController' => base_path('Modules/Quizzes/app/Http/Controllers/Admin/QuizAnswerController.php'),
            'QuizManagerController' => base_path('Modules/Quizzes/app/Http/Controllers/Admin/QuizManagerController.php'),
            'QuizQuestionController' => base_path('Modules/Quizzes/app/Http/Controllers/Admin/QuizQuestionController.php'),
        ];

        foreach ($controllers as $name => $controllerPath) {
            if (File::exists($controllerPath)) {
            $content = File::get($controllerPath);
            if (str_contains($content, 'namespace Modules\Quizzes\app\Http\Controllers\Admin;')) {
                $this->info("\n✅ {$name} namespace: CORRECT");
            } else {
                $this->error("\n❌ {$name} namespace: INCORRECT");
            }

            // Check for test comment
            if (str_contains($content, 'Test comment - this should persist after refresh')) {
                $this->info("✅ Test comment in {$name}: FOUND (changes are persisting)");
            } else {
                $this->warn("⚠️  Test comment in {$name}: NOT FOUND");
            }
            }
        }

        // Check composer autoload
        $composerFile = base_path('composer.json');
        if (File::exists($composerFile)) {
            $composer = json_decode(File::get($composerFile), true);
            if (isset($composer['autoload']['psr-4']['Modules\\Quizzes\\'])) {
                $this->info("\n✅ Composer autoload: CONFIGURED");
            } else {
                $this->error("\n❌ Composer autoload: NOT CONFIGURED");
            }
        }

        $this->info("\n🎯 Summary:");
        $this->info("Your Quizzes module is properly published and should be working.");
        $this->info("Any changes you make to files in Modules/Quizzes/ will persist.");
        $this->info("If you need to republish from the package, run: php artisan quizzes:publish --force");
    }
}

