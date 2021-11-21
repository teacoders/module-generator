<?php

namespace TeaCoders\ModuleGenerator\Console\Commands;

use Illuminate\Support\{
    Str,
    Facades\File,
    Facades\Schema
};
use Illuminate\Console\Command;

use TeaCoders\ModuleGenerator\Console\Commands\Traits\ModuleGeneratorTrait;

class DeleteAll extends Command
{
    use ModuleGeneratorTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:all {name : Module Name} {--c|controller : Delete a controller} {--m|model : Delete a model} {--r|request : Delete a request} {--t|table : Delete a migration} {--b|blade : Delete a view}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will remove the whole module';

    /**
     * File extension.
     *
     * @var string
     */
    const EXTENSION = '.php';
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
        $options = [];
        foreach ($this->options() as $key => $value) {
            if(in_array($key,['controller', 'model', 'request', 'table', 'blade'])){
                $options[$key] = $value;
            }
        }
        $isOptions = in_array(true,$options);
        if(!$isOptions){
            foreach ($options as $key => $value) {
               $this->input->setOption($key, true);
            }
        }
        if($this->option('controller'))
            $this->deleteFile($this->getFilePath('controller'), 'Controller');
        if ($this->option('model'))
            $this->deleteFile($this->getFilePath('model'), 'Model');
        if ($this->option('request'))
            $this->deleteFile($this->getFilePath('request'), 'Request');
        if ($this->option('table'))
            $this->deleteMigration();
        if ($this->option('blade'))
            $this->call('delete:view', [
                'name' => Str::kebab($this->argument('name'))
            ]);
    }
    protected function getFilePath(string $type)
    {
        switch ($type) {
            case 'controller':
                return $this->controllerPath() .$this->argument('name') . "Controller" . self::EXTENSION;
                break;
            case 'model':
                return $this->modelPath() . $this->argument('name'). self::EXTENSION;
                break;
            case 'table':
                return database_path('migrations');
                break;
            case 'request':
                return $this->requestPath() . $this->argument('name') . 'Request'.self::EXTENSION;
                break;
        }
    }
    protected function deleteMigration()
    {
        $path = $this->getFilePath('table');
        $files = array_diff(scandir($path),['.','..']);
        $table =  $this->getPluralName(Str::snake($this->argument('name')));
        $fileName = 'create_' . $table.'_table';
        foreach ($files as $value) {
            if(str_contains($value, $fileName)){
                if (Schema::hasTable($table)) {
                    try {
                        $this->call('migrate:rollback',[
                            '--path' => $path.'/'.$value
                        ]);
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                }
                File::delete($path . '/' . $value);
                $this->info("Migration Deleted Successfully");
            }
        }
    }
    private function deleteFile($path,$type)
    {
        if (file_exists($path)){
            File::delete($path);
            $this->info("{$type} Deleted Successfully");
            if($type == 'Controller')
                $this->deleteRoute();
        }
        else{
            $this->error("{$type} does not exist");
        }
   }
   private function deleteRoute()
   {
        $file = base_path('routes/web.php');
        $fileContent = file_get_contents($file);
        $fileArray = explode("\n", $fileContent);
        $routeName =  $this->getPluralName(Str::kebab($this->argument('name')));
        $controllerName = Str::studly($this->argument('name')).'Controller';
        $diffArray = array_diff($fileArray, ["Route::resource('{$routeName}', '{$controllerName}');"]);
        $content = implode("\n", $diffArray);
        file_put_contents($file, $content);
        $this->info("Route Removed Successfully");
   }
}
