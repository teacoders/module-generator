<?php

namespace TeaCoders\ModuleGenerator\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use TeaCoders\ModuleGenerator\Console\Commands\Traits\ModuleGeneratorTrait;

class GenerateTrait extends Command
{
    use ModuleGeneratorTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:trait {name? : Trait name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will generate trait';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $name;
    protected $file;

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
        if (!is_dir($this->traitPath()))
            mkdir($this->traitPath(), 0777, true);
        $this->file = $this->traitPath() . $this->name . 'Trait.php';
        if (!file_exists($this->file)) {
            File::put($this->file, $this->replaceContent('Teacoders', $this->name, file_get_contents($this->getStub('trait'))));
            $this->info("{$this->name} Trait created successfully 👍");
        } else {
            $this->error("{$this->name} Trait already exist");
        }
    }
}