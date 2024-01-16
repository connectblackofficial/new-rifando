<?php

namespace App\Traits;

trait CrudCommandTrait
{
    private function getControllerNameSpace()
    {
        return ($this->option('controller-namespace')) ? $this->option('controller-namespace') . '\\' : '';
    }

    private function getRequestNameSpace()
    {
        return str_replace("Controllers", "Requests", $this->getControllerNameSpace());
    }

    private function getRequestName()
    {
        return  $this->getModelName() . 'Request';
    }

    private function getModelName()
    {
        return $this->option('model-name');
    }
}