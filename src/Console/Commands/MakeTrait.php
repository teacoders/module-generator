<?php

namespace TeaCoders\ModuleGenerator\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use TeaCoders\ModuleGenerator\Console\Commands\Traits\ModuleGeneratorTrait;

class MakeTrait extends Command
{
    use ModuleGeneratorTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:trait {name* : The name of the classes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new trait classes';

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
        if (!is_dir($this->traitPath()))
            mkdir($this->traitPath(), 0777, true);
        foreach ($this->argument('name') as $name) {
            $file = $this->traitPath() . $name . '.php';
            if (!file_exists($file)) :
                File::put($file, $this->replaceContent('Teacoders', $name, file_get_contents($this->getStub('trait'))));
                $this->info("{$name} created successfully");
            else :
                $this->error("{$name} already exist");
            endif;
        }
    }
}