<?php

namespace TeaCoders\ModuleGenerator\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateView extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:view {name? : Directory name} {--file= : File name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command generate view directory and files';
    protected $name;
    protected $path;
    protected $file;
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
            $this->name = $this->ask('Please Enter View Name');
        $this->path = resource_path('views/' . $this->name);
        $this->file = $this->option('file');
        if (!is_dir($this->path)) {
            mkdir($this->path, 0777, true);
            $this->info("{$this->name} directory created successfully 👍");
        } elseif (!$this->file) {
            $this->error("{$this->name} directory already exist");
        }
        if ($this->file) {
            if (!file_exists($this->path . '/' . $this->file . '.blade.php'))
            $this->createFile($this->file);
            else
                $this->error("{$this->file} file already exist");
        } else {
            foreach (config('modulegenerator.files') as $file) {
                if (!file_exists($this->path . '/' . $file . '.blade.php')) {
                    $this->createFile($file);
                } else {
                    $this->error("{$file} file already exist");
                }
            }
        }
    }
    protected function createFile($file)
    {
        File::put($this->path . '/' . $file . '.blade.php', "<div>This is {$file}</div>");
        $this->info("{$file} file created successfully 👍");
    }
}
