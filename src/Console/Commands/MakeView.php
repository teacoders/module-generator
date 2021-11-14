<?php

namespace TeaCoders\ModuleGenerator\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use TeaCoders\ModuleGenerator\Console\Commands\Traits\ModuleGeneratorTrait;

class MakeView extends Command
{
    use ModuleGeneratorTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:view {name : Directory name} {--file=* : View file name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command generate blank view files for you';

    protected $name;

    protected $path;

    protected $files;

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
        $this->path = $this->viewPath() . $this->argument('name');

        $files = $this->option('file') ?: config('module-generator.files');

        if (!is_dir($this->path)) :

            mkdir($this->path, 0777, true);

            $this->info("{$this->name} directory created successfully");

        elseif (!$files) :

            $this->error("{$this->name} directory already exist");

        endif;

        foreach ($files as $fileName) {

            $file = $this->path . '/' . $fileName . '.blade.php';

            if (!file_exists($file)) :

                File::put($file, file_get_contents($this->getStub('view')));

                $this->info("{$fileName} view file created successfully");
            else :
                $this->error("{$fileName} view file already exist");
            endif;
        }
    }
}