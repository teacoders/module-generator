<?php

namespace TeaCoders\ModuleGenerator\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DeleteView extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:view {name : folder name} {--file= :Remove specific file}';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command will delete view';
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
        $path = resource_path('views/' . $this->argument('name'));
        $this->file = $this->option('file');
        if (file_exists($path)) {
            if (!$this->file) {
                File::deleteDirectory($path);
                $this->info("{$this->argument('name')} directory deleted successfully 👍");
            } elseif (file_exists($path . '/' . $this->file . '.blade.php')) {
                File::delete($path . '/' . $this->file . '.blade.php');
                $this->info("{$this->file} file deleted successfully 👍");
            } else {
                $this->error("{$this->file} file not exist");
            }
        } else {
            $this->error("{$this->argument('name')} directory not exist");
        }
    }
}
