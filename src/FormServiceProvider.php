<?php

namespace Uspdev\Forms;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class FormServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config file
        $this->publishes([
            __DIR__ . '/../config/uspdev-forms.php' => config_path('uspdev-forms.php'),
        ], 'forms-config');

        // Publish migrations
        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations'),
        ], 'forms-migrations');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'uspdev-forms');

        // Registra a diretiva
        Blade::directive('submissionsTable', function ($form) {
            return "<?php echo view('uspdev-forms::partials.submissions-table', ['form' => $form])->render(); ?>";
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/uspdev-forms.php',
            'uspdev-forms'
        );
    }
}
