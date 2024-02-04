<?php

namespace App\Helpers;

use App\Enums\YesNoAsIntEnum;
use Illuminate\Support\Facades\Schema;

class CrudReverseEng
{
    private $table;

    public function __construct($table)
    {
        $this->table = $table;

    }

    public function getCrudFile()
    {
        $f = [];
        $cols = $this->getColumns();

        foreach ($cols as $c => $type) {
            $f['fields'][] = $this->getFieldCrudData($c, $type);
            $f['validations'][] = $this->getFieldRule($c, $type);

        }
        return json_encode($f);

    }

    public function getColumns()
    {
        $cols = [];
        $columns = Schema::getColumnListing($this->table);

        foreach ($columns as $column) {
            $type = Schema::getColumnType($this->table, $column);
            $cols[$column] = $type;
        }
        return $cols;
    }

    public function getFieldCrudData($col, $type)
    {
        if ($type == 'boolean') {
            return [
                'name' => $col,
                'type' => 'select',
                'options' => ['0' => 'no', '1' => 'yes']
            ];
        }
        return ['name' => $col,
            'type' => $type];
    }

    public
    function getFieldRule($col, $type)
    {

        if ($type == 'string') {
            return [
                'field' => $col,
                'rules' => 'required|max:196'
            ];
        }
        if ($type == 'boolean') {
            return [
                'field' => $col,
                'rules' => 'required|in:0,1'
            ];
        }
        return [
            'field' => $col,
            'rules' => 'required'
        ];
    }
}