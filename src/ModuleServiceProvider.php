<?php

namespace TeaCoders\ModuleGenerator;

use Illuminate\Support\ServiceProvider;
use TeaCoders\ModuleGenerator\Console\Commands\DeleteView;
use TeaCoders\ModuleGenerator\Console\Commands\DeleteTrait;
use TeaCoders\ModuleGenerator\Console\Commands\MakeAll;
use TeaCoders\ModuleGenerator\Console\Commands\MakeView;
use TeaCoders\ModuleGenerator\Console\Commands\MakeTrait;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/module-generator.php' => config_path('module-generator.php'),
        ], 'publish module generator config file');
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeView::class,
                MakeAll::class,
                MakeTrait::class,
                DeleteView::class,
                DeleteTrait::class,
            ]);
        }
    }
}
