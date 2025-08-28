<?php

namespace admin\quizzes\Console\Commands;

use Illuminate\Console\Command;

class DebugQuizzesCommand extends Command
{
    protected $signature = 'quizzes:debug';
    protected $description = 'Debug Quizzes module configuration and view resolution';

    public function handle()
    {
        $this->info('🔍 Debugging Quizzes Module...');

        // Check route file loading
        $this->info("\n📍 Route Files:");
        $moduleRoutes = base_path('Modules/Quizzes/routes/web.php');
        if (File::exists($moduleRoutes)) {
            $this->info("✅ Module routes found: {$moduleRoutes}");
            $this->info("   Last modified: " . date('Y-m-d H:i:s', filemtime($moduleRoutes)));
        } else {
            $this->error("❌ Module routes not found");
        }

        $packageRoutes = base_path('packages/admin/quizzes/src/routes/web.php');
        if (File::exists($packageRoutes)) {
            $this->info("✅ Package routes found: {$packageRoutes}");
            $this->info("   Last modified: " . date('Y-m-d H:i:s', filemtime($packageRoutes)));
        } else {
            $this->error("❌ Package routes not found");
        }
        
        // Check view loading priority
        $this->info("\n👀 View Loading Priority:");
        $viewPaths = [
            'Module views' => base_path('Modules/Quizzes/resources/views'),
            'Published views' => resource_path('views/admin/quizzes'),
            'Package views' => base_path('packages/admin/quizzes/resources/views'),
        ];
        
        foreach ($viewPaths as $name => $path) {
            if (File::exists($path)) {
                $this->info("✅ {$name}: {$path}");
            } else {
                $this->warn("⚠️  {$name}: NOT FOUND - {$path}");
            }
        }
        
        // Check controller resolution
        $this->info("\n🎯 Controller Resolution:");
        $controllers = [
           'QuizAnswerController' => base_path('Modules/Quizzes/app/Http/Controllers/Admin/QuizAnswerController.php'),
            'QuizManagerController' => base_path('Modules/Quizzes/app/Http/Controllers/Admin/QuizManagerController.php'),
            'QuizQuestionController' => base_path('Modules/Quizzes/app/Http/Controllers/Admin/QuizQuestionController.php'),
        ];

         foreach ($controllers as $label => $controllerClass) {
            $this->info("Checking {$label}: {$controllerClass}");
            if (class_exists($controllerClass)) {
            $this->info("✅ Controller class found: {$controllerClass}");
            $reflection = new \ReflectionClass($controllerClass);
            $this->info("   File: " . $reflection->getFileName());
            $this->info("   Last modified: " . date('Y-m-d H:i:s', filemtime($reflection->getFileName())));
            } else {
            $this->error("❌ Controller class not found: {$controllerClass}");
            }
        }

       // Show current routes
        $this->info("\n🛣️  Current Routes:");
        $routes = Route::getRoutes();
        $quizRoutes = [];

        foreach ($routes as $route) {
            $action = $route->getAction();
            if (isset($action['controller'])) {
            if (
                str_contains($action['controller'], 'QuizAnswerController') ||
                str_contains($action['controller'], 'QuizManagerController')   ||
                str_contains($action['controller'], 'QuizQuestionController')
            ) {
                $quizRoutes[] = [
                'uri' => $route->uri(),
                'methods' => implode('|', $route->methods()),
                'controller' => $action['controller'],
                'name' => $route->getName(),
                ];
            }
            }
        }
        
        if (!empty($quizRoutes)) {
            $this->table(['URI', 'Methods', 'Controller', 'Name'], $quizRoutes);
        } else {
            $this->warn("No shipping routes found.");
        }
    }
}

