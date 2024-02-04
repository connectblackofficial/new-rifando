<?php

namespace App\Console\Commands;

use App\Traits\CrudCommandTrait;
use File;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CrudCommand extends Command
{
    use CrudCommandTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:generate
                            {name : The name of the Crud.}
                            {--fields= : Field names for the form & migration.}
                            {--fields_from_file= : Fields from a json file.}
                            {--validations= : Validation rules for the fields.}
                            {--controller-namespace= : Namespace of the controller.}
                            {--model-namespace= : Namespace of the model inside "app" dir.}
                            {--pk=id : The name of the primary key.}
                            {--pagination=25 : The amount of models per page for index pages.}
                            {--indexes= : The fields to add an index to.}
                            {--foreign-keys= : The foreign keys for the table.}
                            {--relationships= : The relationships for the model.}
                            {--route=yes : Include Crud route to routes.php? yes|no.}
                            {--route-group= : Prefix of the route group.}
                            {--view-path= : The name of the view path.}
                            {--form-helper=html : Helper for generating the form.}
                            {--localize=no : Allow to localize? yes|no.}
                            {--locales=en : Locales language type.}
                            {--soft-deletes=no : Include soft deletes fields.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Crud including controller, model, views & migrations.';

    /** @var string */
    protected $routeName = '';

    /** @var string */
    protected $controller = '';

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
        $name = $this->argument('name');
        $modelName = Str::singular($name);
        $migrationName = Str::plural(Str::snake($name));
        $tableName = $migrationName;

        $routeGroup = $this->option('route-group');
        $this->routeName = ($routeGroup) ? $routeGroup . '/' . Str::snake($name, '-') : Str::snake($name, '-');
        $perPage = intval($this->option('pagination'));

        $controllerNamespace = $this->getControllerNameSpace();

        $requestNameSpace = str_replace("Controllers", "Requests", $controllerNamespace);
        $modelNamespace = ($this->option('model-namespace')) ? trim($this->option('model-namespace')) . '\\' : 'Models\\';


        $fields = rtrim($this->option('fields'), ';');

        if ($this->option('fields_from_file')) {
            $fields = $this->processJSONFields($this->option('fields_from_file'));
        }

        $primaryKey = $this->option('pk');
        $viewPath = $this->option('view-path');

        $foreignKeys = $this->option('foreign-keys');

        if ($this->option('fields_from_file')) {
            $foreignKeys = $this->processJSONForeignKeys($this->option('fields_from_file'));
        }

        $validations = trim($this->option('validations'));
        if ($this->option('fields_from_file')) {
            $validations = $this->processJSONValidations($this->option('fields_from_file'));
        }

        $fieldsArray = explode(';', $fields);
        $fillableArray = [];
        $migrationFields = '';

        foreach ($fieldsArray as $item) {
            $spareParts = explode('#', trim($item));
            $fillableArray[] = $spareParts[0];
            $modifier = !empty($spareParts[2]) ? $spareParts[2] : 'nullable';

            // Process migration fields
            $migrationFields .= $spareParts[0] . '#' . $spareParts[1];
            $migrationFields .= '#' . $modifier;
            $migrationFields .= ';';
        }

        $commaSeparetedString = implode("', '", $fillableArray);
        $fillable = "['" . $commaSeparetedString . "']";

        $localize = $this->option('localize');
        $locales = $this->option('locales');

        $indexes = $this->option('indexes');
        $relationships = $this->option('relationships');
        if ($this->option('fields_from_file')) {
            $relationships = $this->processJSONRelationships($this->option('fields_from_file'));
        }

        $formHelper = $this->option('form-helper');
        $softDeletes = $this->option('soft-deletes');

        $this->call('crud:request', ['name' => $requestNameSpace . $modelName . 'Request', '--request-namespace' => $requestNameSpace, '--fields' => $fields, '--validations' => $validations, '--view-path' => $viewPath, '--route-group' => $routeGroup]);

        $controllerData = [
            'name' => $controllerNamespace . $name . 'Controller',
            '--crud-name' => $name,
            '--model-name' => $modelName,
            '--model-namespace' => $modelNamespace,
            '--view-path' => $viewPath,
            '--route-group' => $routeGroup,
            '--pagination' => $perPage,
            '--fields' => $fields,
            '--validations' => $validations,
            '--controller-namespace' => $this->getControllerNameSpace()
        ];
        $this->call('crud:controller', $controllerData);
        $this->call('crud:model', ['--fields' => $fields, 'name' => $modelNamespace . $modelName, '--fillable' => $fillable, '--table' => $tableName, '--pk' => $primaryKey, '--relationships' => $relationships, '--soft-deletes' => $softDeletes]);
        $this->call('crud:migration', ['name' => $migrationName, '--schema' => $migrationFields, '--pk' => $primaryKey, '--indexes' => $indexes, '--foreign-keys' => $foreignKeys, '--soft-deletes' => $softDeletes]);
        $this->call('crud:view', ['name' => $name, '--fields' => $fields, '--validations' => $validations, '--view-path' => $viewPath, '--route-group' => $routeGroup, '--localize' => $localize, '--pk' => $primaryKey, '--form-helper' => $formHelper]);


        if ($localize == 'yes') {
            $this->call('crud:lang', ['name' => $name, '--fields' => $fields, '--locales' => $locales]);
        }

        // For optimizing the class loader
        if (\App::VERSION() < '5.6') {
            $this->callSilent('optimize');
        }

        // Updating the Http/routes.php file
        $routeFile = app_path('Http/routes.php');

        if (\App::VERSION() >= '5.3') {
            $routeFile = base_path('routes/web.php');
        }

        if (file_exists($routeFile) && (strtolower($this->option('route')) === 'yes')) {
            $this->controller = ($controllerNamespace != '') ? $controllerNamespace . $name . 'Controller' : $name . 'Controller';

            $isAdded = File::append($routeFile, "\n" . implode("\n", $this->addRoutes()));

            if ($isAdded) {
                $this->info('Crud/Resource route added to ' . $routeFile);
            } else {
                $this->info('Unable to add the route to ' . $routeFile);
            }
        }
    }

    /**
     * Add routes.
     *
     * @return  array
     */
    protected function addRoutes()
    {
        /*
         * // Rota para exibir a lista de usuários
Route::get('admin/user', 'App\Http\Controllers\SuperAdmin\UserController@index')
    ->name('admin.user.index');

// Rota para exibir o formulário de criação de um novo usuário
Route::get('admin/user/create', 'App\Http\Controllers\SuperAdmin\UserController@create')
    ->name('admin.user.create');

// Rota para armazenar um novo usuário
Route::post('admin/user', 'App\Http\Controllers\SuperAdmin\UserController@store')
    ->name('admin.user.store');

// Rota para exibir um usuário específico
Route::get('admin/user/{user}', 'App\Http\Controllers\SuperAdmin\UserController@show')
    ->name('admin.user.show');

// Rota para exibir o formulário de edição de um usuário
Route::get('admin/user/{user}/edit', 'App\Http\Controllers\SuperAdmin\UserController@edit')
    ->name('admin.user.edit');

// Rota para atualizar um usuário
Route::put('admin/user/{user}', 'App\Http\Controllers\SuperAdmin\UserController@update')
    ->name('admin.user.update');

// Rota para deletar um usuário
Route::delete('admin/user/{user}', 'App\Http\Controllers\SuperAdmin\UserController@destroy')
    ->name('admin.user.destroy');

         */
        $routeNamePrefix = str_replace("/", ".", $this->routeName);

        $routes = [];
        $resourcesList = [];
        $resourcesList['index'] = 'get';
        $resourcesList['create'] = 'get';
        $resourcesList['store'] = 'post';
        $resourcesList['show'] = 'get';
        $resourcesList['edit'] = 'get';
        $resourcesList['update'] = 'put';
        $resourcesList['destroy'] = 'delete';
        $hasIdsMethods = ['show', 'edit', 'update', 'destroy'];
        foreach ($resourcesList as $method => $protocol) {
            $routeName = $this->routeName;
            if (in_array($method, $hasIdsMethods)) {
                if ($method == 'edit') {
                    $routeName = "$routeName/{pk}/edit";
                } else {
                    $routeName = "$routeName/{pk}";
                }
            }
            if ($method == 'create') {
                $routeName .= "/create";
            }
            $routes[] = "Route::$protocol('" . $routeName . "', [\\" . $this->controller . "::class, '$method'])->name('" . $routeNamePrefix . ".$method');";
        }

        return $routes;
    }

    /**
     * Process the JSON Fields.
     *
     * @param string $file
     *
     * @return string
     */
    protected function processJSONFields($file)
    {
        $json = File::get($file);
        $fields = json_decode($json);

        $fieldsString = '';
        foreach ($fields->fields as $field) {
            if ($field->type === 'select' || $field->type === 'enum') {
                $fieldsString .= $field->name . '#' . $field->type . '#options=' . json_encode($field->options) . ';';
            } else {
                $fieldsString .= $field->name . '#' . $field->type . ';';
            }
        }

        $fieldsString = rtrim($fieldsString, ';');

        return $fieldsString;
    }

    /**
     * Process the JSON Foreign keys.
     *
     * @param string $file
     *
     * @return string
     */
    protected function processJSONForeignKeys($file)
    {
        $json = File::get($file);
        $fields = json_decode($json);

        if (!property_exists($fields, 'foreign_keys')) {
            return '';
        }

        $foreignKeysString = '';
        foreach ($fields->foreign_keys as $foreign_key) {
            $foreignKeysString .= $foreign_key->column . '#' . $foreign_key->references . '#' . $foreign_key->on;

            if (property_exists($foreign_key, 'onDelete')) {
                $foreignKeysString .= '#' . $foreign_key->onDelete;
            }

            if (property_exists($foreign_key, 'onUpdate')) {
                $foreignKeysString .= '#' . $foreign_key->onUpdate;
            }

            $foreignKeysString .= ',';
        }

        $foreignKeysString = rtrim($foreignKeysString, ',');

        return $foreignKeysString;
    }

    /**
     * Process the JSON Relationships.
     *
     * @param string $file
     *
     * @return string
     */
    protected function processJSONRelationships($file)
    {
        $json = File::get($file);
        $fields = json_decode($json);

        if (!property_exists($fields, 'relationships')) {
            return '';
        }

        $relationsString = '';
        foreach ($fields->relationships as $relation) {
            $relationsString .= $relation->name . '#' . $relation->type . '#' . $relation->class . ';';
        }

        $relationsString = rtrim($relationsString, ';');

        return $relationsString;
    }

    /**
     * Process the JSON Validations.
     *
     * @param string $file
     *
     * @return string
     */
    protected function processJSONValidations($file)
    {
        $json = File::get($file);
        $fields = json_decode($json);

        if (!property_exists($fields, 'validations')) {
            return '';
        }

        $validationsString = '';
        foreach ($fields->validations as $validation) {

            $validationsString .= $validation->field . '#' . $validation->rules . ';';
        }

        $validationsString = rtrim($validationsString, ';');

        return $validationsString;
    }
}
