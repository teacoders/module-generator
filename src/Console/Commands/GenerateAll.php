<?php

namespace TeaCoders\ModuleGenerator\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use TeaCoders\ModuleGenerator\Console\Commands\Traits\ModuleGeneratorTrait;

class GenerateAll extends Command
{
    use ModuleGeneratorTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:all {name? : Enter Module Name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command will create model,controller,migration and view';
    protected $name;
    protected $controller;
    protected $viewDirectoryName;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->name = $this->argument('name');
        if (!$this->name)
            $this->name = $this->ask('Enter Module name');
        $this->name = ucfirst(Str::pluralStudly($this->name, 1));
        $this->viewDirectoryName = strtolower($this->name);
        $this->controller = $this->name . 'Controller';
        $this->output->progressStart(100);
        for ($i = 0; $i < 100; $i++) {
            usleep(25000);
            $this->output->progressAdvance();
        }
        $this->replaceControllerName();
        $this->createModel();
        Artisan::call("make:migration create_{$this->getPluralName(Str::snake($this->name))}_table");
        $this->generateRoute();
        $this->generateView();
        $this->output->progressFinish();
        $this->info("\nModule generated successfully 👍");
    }
    protected function replaceControllerName()
    {
        $file = $this->getControllerNamespace() . $this->name . 'Controller.php';
        if (!file_exists($file)) {
            $newContent = $this->replaceContent('Teacoder', $this->name, file_get_contents($this->getStub('controller')));
            File::put($file, $newContent);
            $this->replaceVariableName($file, $newContent);
        } else {
            $this->error("\nController already exist");
        }
    }
    protected function replaceVariableName($path, $content)
    {
        $newContent = $this->replaceContent('$teacoder', '$' . $this->viewDirectoryName, $content);
        File::put($path, $newContent);
        $this->replaceRedirectPath($path, $newContent);
    }
    protected function replaceRedirectPath($path, $content)
    {
        $newContent = $this->replaceContent('teacoders', $this->getPluralName($this->viewDirectoryName), $content);
        File::put($path, $newContent);
        $this->replaceViewDirectoryName($path, $newContent);
    }
    protected function replaceViewDirectoryName($path, $content)
    {
        File::put($path, $this->replaceContent('teacoder', $this->viewDirectoryName, $content));
    }
    protected function createModel()
    {
        $file = app_path($this->name . '.php');
        if (!file_exists($file)) {
            File::put($file, $this->replaceContent('Teacoders', $this->name, file_get_contents($this->getStub('model'))));
        } else {
            $this->error("\nModel already exist");
        }
    }
    protected function generateRoute()
    {
        if (file_exists($this->getControllerPath()))
            File::append($this->routePath(), "\nRoute::resource('{$this->getPluralName($this->viewDirectoryName)}', '{$this->controller}');");
        else
            $this->info("\nController not found");
    }
    protected function generateView()
    {
        if (!is_dir($this->getViewPath()))
            mkdir($this->getViewPath(), 0777, true);
        else
            $this->error("\nView directory already exist");
        if (file_exists(config_path('modulegenerator.php')))
        foreach (config('modulegenerator.files') as $file) {
            if (!file_exists($this->getViewPath() . $file . '.blade.php'))
            File::put($this->getViewPath() . $file . '.blade.php', "<div>This is {$file} flle</div>");
            else
                $this->error("\n $file file already exist");
        }
        else
            $this->error('Please publish modulegenerator config file');
    }
}
