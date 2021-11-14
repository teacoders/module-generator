<?php

namespace TeaCoders\ModuleGenerator\Console\Commands\Traits;

use Illuminate\Support\Str;

/**
 * ModuleGeneratorTrait
 */
trait ModuleGeneratorTrait
{
    public function getStub(string $type)
    {
        return __DIR__ . '/../stubs/' . $type . '.stub';
    }
    public function getPluralName($name)
    {
        return Str::plural(strtolower($name), 2);
    }
    public function controllerPath()
    {
        return app_path('Http/Controllers/');
    }
    public function viewPath()
    {
        return resource_path('views/');
    }
    public function replaceContent($search, $replace, $content)
    {
        return str_replace($search, $replace, $content);
    }
    public function routePath()
    {
        return base_path('routes/web.php');
    }
    public function traitPath()
    {
        return app_path('Http/');
    }
    public function modelPath()
    {
        return app_path() . '/';
    }
    public function requestPath()
    {
        return app_path('Http/Requests/');
    }
}