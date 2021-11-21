<?php

namespace TeaCoders\ModuleGenerator\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\{
    Str,
    Facades\File
};
use TeaCoders\ModuleGenerator\Console\Commands\Traits\ModuleGeneratorTrait;

class MakeAll extends Command
{
    use ModuleGeneratorTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:all {name? : Module Name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will create model, controller, migration, request, view and route';
    protected $name;
    protected $controller;
    protected $viewDirectory;
    protected $migrationColumns = [];
    protected $lastKey;
    private   $addRequestClass;

    public const FILE_EXTENSION = '.php';
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
        $addColumn =  $this->confirm('Do you want to add columns in migration ?');
        if ($addColumn)
            $this->addColumn();
        $this->addRequestClass = $this->confirm('Do you want to create request class ?');

        $this->name = ucfirst(Str::pluralStudly($this->name, 1));
        $this->viewDirectory = Str::kebab($this->name);
        $this->controller = $this->name . 'Controller';
        $this->output->progressStart(100);
        for ($i = 0; $i < 100; $i++) {
            $this->output->progressAdvance();
        }
        $this->replaceControllerName();
        $this->createModel();
        $this->generateMigration();
        $this->generateRoute();
        $this->generateView();
        $this->output->progressFinish();
        $this->info("Module generated successfully 👍");
    }
    /**
     * Create controller and replace controller name
     */
    protected function replaceControllerName()
    {
        $controller = $this->controllerPath() . $this->controller . self::FILE_EXTENSION;
        if (!file_exists($controller)) {
            $stub = $this->addRequestClass ? 'controller-with-request-class' : 'controller';
            $newContent = $this->replaceContent('Teacoder', $this->name, file_get_contents($this->getStub($stub)));
            // File::put($controller, $newContent);
            $this->replaceVariable($controller, $newContent);
            if (!empty($this->migrationColumns && !$this->addRequestClass))
                $this->addValidation($controller);
            elseif ($this->addRequestClass)
                $this->createRequest();
        } else {
            $this->error("\nController already exist");
        }
    }
    protected function replaceVariable($path, $content)
    {
        $variableName = Str::camel($this->name);
        $newContent = $this->replaceContent('$teacoder', '$' . $variableName, $content);
        $newContent = $this->replaceContent('teacoderVariable', $variableName, $newContent);
        $newContent = $this->replaceContent('teacoderlist', Str::plural(Str::snake($this->name), 2), $newContent);
        File::put($path, $newContent);
        $this->replaceRedirectPath($path, $newContent);
    }
    protected function createRequest()
    {
        if (!is_dir($this->requestPath()))
            mkdir($this->requestPath());
        $request = $this->requestPath() . $this->name . 'Request' . self::FILE_EXTENSION;
        if (!file_exists($request)) {
            $newContent = $this->replaceContent('Teacoder', $this->name, file_get_contents($this->getStub('request')));
            File::put($request, $newContent);
            $this->addValidation($request);
        }
    }
    protected function replaceRedirectPath($path, $content)
    {
        $newContent = $this->replaceContent('teacodersroute', $this->getPluralName($this->viewDirectory) . '.index', $content);
        File::put($path, $newContent);
        $this->replaceViewDirectoryName($path, $newContent);
    }
    public function addValidation($file)
    {
        if (!empty($this->migrationColumns)) {
            $this->lastKey = key(array_slice($this->migrationColumns, -1, 1, true));
            foreach ($this->migrationColumns as $key => $value) {
                $break = ($key === $this->lastKey) ? '' : "\n";
                $content[] = "'{$key}'  => 'required',{$break}" . str_repeat("\t", 3);
            }
            File::put($file, $this->replaceContent('//validations', implode($content), file_get_contents($file)), true);
        }
    }
    protected function replaceViewDirectoryName($path, $content)
    {
        File::put($path, $this->replaceContent('teacodersview', $this->viewDirectory, $content));
    }
    protected function createModel()
    {
        $file = $this->modelPath() . $this->name . self::FILE_EXTENSION;
        if (!file_exists($file))
            File::put($file, $this->replaceContent('Teacoder', $this->name, file_get_contents($this->getStub('model'))));
        else
            $this->error("\nModel already exist");
    }
    protected function generateRoute()
    {
        if (file_exists($this->controllerPath())) {
            $url = Str::kebab($this->getPluralName($this->viewDirectory));
            File::append($this->routePath(), "\nRoute::resource('{$url}', App\\Http\\Controllers\\$this->controller::class);");
        } else {
            $this->info("\nController not found");
        }
    }
    protected function generateView()
    {
        $view = $this->viewPath() . $this->viewDirectory . '/';
        if (!is_dir($view))
            mkdir($view, 0777, true);
        else
            $this->error("\nView directory already exist");
        if (file_exists(config_path('module-generator.php'))) {
            foreach (config('module-generator.files') as $file) {
                if (!file_exists($view . $file . '.blade' . self::FILE_EXTENSION))
                    File::put($view . $file . '.blade' . self::FILE_EXTENSION, file_get_contents($this->getStub('view')));
                else
                    $this->error("\n" . $file . ' file already exist');
            }
        } else {
            $this->error('Please publish module generator config file');
        }
    }
    public function generateMigration()
    {
        $table = $this->getPluralName(Str::snake($this->name));
        $file = database_path('migrations/' . date('Y_m_d_His') . '_create_' . $table . '_table' . self::FILE_EXTENSION);
        $stub = !empty($this->migrationColumns) ? 'migration-with-column' : 'migration';
        if (!file_exists($file)) {
            File::put($file, $this->replaceContent('MigrationFileName', 'Create' . Str::plural($this->name) . 'Table', file_get_contents($this->getStub($stub))));
            $this->replaceTableName($file);
        } else {
            $this->error("\Migration already exist");
        }
    }
    public function replaceTableName($file)
    {
        File::put($file, $this->replaceContent('table_name', $this->getPluralName(Str::snake($this->name)), file_get_contents($file)));
        if (!empty($this->migrationColumns))
            $this->addColumnsToMigration($file);
    }
    public function addColumnsToMigration($file)
    {
        foreach ($this->migrationColumns as $key => $value) {
            $break = ($key === $this->lastKey) ? '' : "\n" . str_repeat("\t", 3);
            $content[] = '$table->' . $value . "('$key');" . $break;
        }
        File::put($file, $this->replaceContent('//teacodersColumns', implode($content), file_get_contents($file)));
    }
    public function addColumn()
    {
        $this->alert('example: name,description,avatar');
        $columns = $this->ask('Enter Column name with comman seperated');
        if (strpos($columns, ',')) {
            $columns = explode(',', trim($columns, ','));
            foreach ($columns as $value) {
                $this->selectDataType($value);
            }
        } else {
            $this->selectDataType($columns);
        }
        return;
    }
    public function selectDataType($columnName)
    {
        $rows = array_chunk($this->getDataTypes(), 4);
        $this->table(["Please Select Data Type For {$columnName}"], $rows);
        $columnType = $this->anticipate("Enter Data Type of {$columnName} ?", $this->getDataTypes());
        if (!$columnType || !in_array($columnType, $this->getDataTypes())) {
            $this->error('Please select valid data type');
            $this->selectDataType($columnName);
        } else {
            return $this->migrationColumns[$columnName] = $columnType;
        }
    }
    private function getDataTypes(): array
    {
        return [
            'bigIncrements',
            'bigInteger',
            'binary',
            'boolean',
            'char',
            'dateTimeTz',
            'dateTime',
            'date',
            'decimal',
            'double',
            'enum',
            'float',
            'foreignId',
            'foreignIdFor',
            'foreignUuid',
            'geometryCollection',
            'geometry',
            'id',
            'increments',
            'integer',
            'ipAddress',
            'json',
            'jsonb',
            'lineString',
            'longText',
            'macAddress',
            'mediumIncrements',
            'mediumInteger',
            'mediumText',
            'morphs',
            'multiLineString',
            'multiPoint',
            'multiPolygon',
            'nullableMorphs',
            'nullableTimestamps',
            'nullableUuidMorphs',
            'point',
            'polygon',
            'rememberToken',
            'set',
            'smallIncrements',
            'smallInteger',
            'softDeletesTz',
            'softDeletes',
            'string',
            'text',
            'timeTz',
            'time',
            'timestampTz',
            'timestamp',
            'timestampsTz',
            'timestamps',
            'tinyIncrements',
            'tinyInteger',
            'tinyText',
            'unsignedBigInteger',
            'unsignedDecimal',
            'unsignedInteger',
            'unsignedMediumInteger',
            'unsignedSmallInteger',
            'unsignedTinyInteger',
            'uuidMorphs',
            'uuid',
            'year'
        ];
    }
}