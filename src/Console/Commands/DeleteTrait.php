<?php

namespace TeaCoders\ModuleGenerator\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use TeaCoders\ModuleGenerator\Console\Commands\Traits\ModuleGeneratorTrait;

class DeleteTrait extends Command
{
    use ModuleGeneratorTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:trait {name* : The name of the classes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete multiple trait classes';

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
        if (!is_dir($this->traitPath())) :

            $this->error("Traits directory not found");
        else :
            foreach ($this->argument('name') as $name) {

                $file = $this->traitPath() . $name . '.php';

                if (file_exists($file)) :

                    File::delete($file);

                    $this->info("{$name} trait deleted successfully");
                else :
                    $this->error("{$name} does not exist");
                endif;
            }
        endif;
    }
}