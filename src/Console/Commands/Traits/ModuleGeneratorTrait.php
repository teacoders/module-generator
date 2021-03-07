<?php

namespace TeaCoders\ModuleGenerator\Console\Commands\Traits;

use Illuminate\Support\Str;

/**
 * ModuleGeneratorTrait
 */
trait ModuleGeneratorTrait
{
    protected function getStub(string $type)
    {
        return __DIR__ . '/../stubs/' . $type . '.stub';
    }
    protected function getPluralName($name)
    {
        return Str::plural(strtolower($name), 2);
    }
    protected function getControllerPath()
    {
        return app_path('Http/Controllers/' . $this->name . 'Controller.php');
    }
    protected function getControllerNamespace()
    {
        return app_path('Http/Controllers/');
    }
    protected function getViewPath()
    {
        return resource_path('views/' . $this->viewDirectoryName . '/');
    }
    protected function replaceContent($search, $replace, $content)
    {
        return str_replace($search, $replace, $content);
    }
    protected function routePath()
    {
        return base_path('routes/web.php');
    }
    protected function traitPath()
    {
        return app_path('Http/Traits/');
    }
}
