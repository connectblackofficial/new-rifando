<?php

namespace App;

use App\Traits\ModelSiteOwnerTrait;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use ModelSiteOwnerTrait;

    protected $fillable = [
        'title',
        'link',
        'user_id'
    ];
}
