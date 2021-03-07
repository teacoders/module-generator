<?php

namespace TeaCoders\ModuleGenerator;

use Illuminate\Support\ServiceProvider;
use TeaCoders\ModuleGenerator\Console\Commands\DeleteView;
use TeaCoders\ModuleGenerator\Console\Commands\DeleteTrait;
use TeaCoders\ModuleGenerator\Console\Commands\GenerateAll;
use TeaCoders\ModuleGenerator\Console\Commands\GenerateView;
use TeaCoders\ModuleGenerator\Console\Commands\GenerateTrait;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/modulegenerator.php' => config_path('modulegenerator.php'),
        ], 'publish module generator config file');
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateView::class,
                GenerateAll::class,
                GenerateTrait::class,
                DeleteView::class,
                DeleteTrait::class,
            ]);
        }
    }
}
