<?php

namespace App\Rules;

use App\Models\Site;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class UniqueOnSite implements Rule
{
    private $model;
    private $col;
    private $site;
    private $ownerCol;

    public function __construct(Site $site, string $model, string $col, $ownerCol = 'user_id')
    {
        $this->col = $col;
        $this->model = $model;
        $this->site = $site;
        $this->ownerCol = $ownerCol;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $table = (new $this->model)->getTable();
        return !\DB::table($table)->where($this->ownerCol, $this->site->user_id)->where($this->col, $value)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Já existe um registro com este valor.';
    }
}
