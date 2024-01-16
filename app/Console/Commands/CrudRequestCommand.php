<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class CrudRequestCommand extends GeneratorCommand
{
    protected $signature = 'crud:request
                            {name : The name of the controler.}
                            {--crud-name= : The name of the Crud.}
                            {--model-name= : The name of the Model.}
                            {--model-namespace= : The namespace of the Model.}
                            {--controller-namespace= : Namespace of the controller.}
                            {--request-namespace= : Namespace of the request.}
                            {--view-path= : The name of the view path.}
                            {--fields= : Field names for the form & migration.}
                            {--validations= : Validation rules for the fields.}
                            {--route-group= : Prefix of the route group.}
                            {--pagination=25 : The amount of models per page for index pages.}
                            {--force : Overwrite already existing controller.}';

    protected $type = 'Request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';



    protected function getStub()
    {
        return config('crudgenerator.custom_template')
            ? config('crudgenerator.path') . '/request.stub'
            : __DIR__ . '/../stubs/request.stub';
    }


    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());
        $validations = rtrim($this->option('validations'), ';');
        $validationRules = '';
        if (trim($validations) != '') {
            $validationRules = PHP_EOL;
            $rules = explode(';', $validations);
            foreach ($rules as $v) {
                if (trim($v) == '') {
                    continue;
                }
                // extract field name and args
                $parts = explode('#', $v);
                $fieldName = trim($parts[0]);
                $rules = trim($parts[1]);
                $validationRules .= "\n\t\t\t'$fieldName' => '$rules',";
            }
            $validationRules = substr($validationRules, 0, -1); // lose the last comma
            $validationRules .= "\n\t\t";
        }


        return $this->replaceNamespace($stub, $name)
            ->replaceValidationRules($stub, $validationRules)
            ->replaceClass($stub, $name);
    }
    protected function alreadyExists($rawName)
    {
        if ($this->option('force')) {
            return false;
        }
        return parent::alreadyExists($rawName);
    }

    protected function replaceValidationRules(&$stub, $validationRules)
    {
        $stub = str_replace('{{validationRules}}', $validationRules, $stub);

        return $this;
    }


    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\\' . ($this->option('request-namespace') ? $this->option('request-namespace') : 'Http\Requests');
    }
}
