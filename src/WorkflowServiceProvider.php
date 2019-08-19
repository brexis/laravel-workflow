<?php

namespace Vimily\LaravelWorkflow;

use Illuminate\Support\ServiceProvider;

/**
 * @author Boris Koumondji <Vimily@yahoo.fr>
 */
class WorkflowServiceProvider extends ServiceProvider
{
    protected $commands = [
        'Vimily\LaravelWorkflow\Commands\WorkflowDumpCommand',
    ];

    /**
    * Bootstrap the application services...
    *
    * @return void
    */
    public function boot()
    {
        $configPath = $this->configPath();

        $this->publishes([
            $configPath => config_path('workflow.php')
        ], 'config');
    }

    /**
    * Register the application services.
    *
    * @return void
    */
    public function register()
    {
        $this->mergeConfigFrom(
            $this->configPath(),
            'workflow'
        );

        $this->commands($this->commands);

        $this->app->singleton(
            'workflow', function ($app) {
                return new WorkflowRegistry($app['config']->get('workflow'));
            }
        );
    }

    protected function configPath()
    {
        return __DIR__ . '/../config/workflow.php';
    }

    /**
    * Get the services provided by the provider.
    *
    * @return array
    */
    public function provides()
    {
        return ['workflow'];
    }
}
