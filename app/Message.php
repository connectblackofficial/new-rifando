<?php

namespace App;

use App\Traits\ModelSiteOwnerTrait;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use ModelSiteOwnerTrait;

    protected $fillable = ['title', 'body','user_id'];
}
