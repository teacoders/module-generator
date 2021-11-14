<?php

namespace TeaCoders\ModuleGenerator\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use TeaCoders\ModuleGenerator\Console\Commands\Traits\ModuleGeneratorTrait;

class DeleteView extends Command
{
    use ModuleGeneratorTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:view {name : Directory name} {--file= : Remove specific files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command will delete the view directory or specific view file';

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
        $path = $this->viewPath() . $this->argument('name');

        $this->file = $this->option('file');

        if (is_dir($path)) :
            if (!$this->file) :
                File::deleteDirectory($path);
                $this->info("view directory deleted successfully");
            else :
                $this->deleteViewFile($path);
            endif;
        else :
            $this->error("{$this->argument('name')} directory does not exist");
        endif;
    }
    protected function deleteViewFile($path)
    {
        $file = $path . '/' . $this->file . '.blade.php';

        if (file_exists($file)) :
            File::delete($file);
            $this->info("{$this->file} view file deleted successfully");
        else :
            $this->error("{$this->file} view file does not exist");
        endif;
    }
}
