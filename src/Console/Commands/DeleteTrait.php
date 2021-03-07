<?php

namespace TeaCoders\ModuleGenerator\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DeleteTrait extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:trait {name? : Trait name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will delete trait';

    protected $name;
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
            $this->name = $this->ask('Enter Trait Name');
        $this->name = ucfirst($this->name);
        $file = $this->traitPath() . $this->name . 'Trait.php';
        if (!is_dir($this->traitPath())) {
            $this->error("Traits directory not found");
        } elseif (file_exists($file)) {
            File::delete($file);
            $this->info("{$this->name} Trait deleted successfully 👍");
        } else {
            $this->error("{$this->name} Trait not found");
        }
    }
}
