<?php

namespace App\Traits;

use App\Exceptions\UserErrorException;
use App\Models\User;

trait ModelAcessControllTrait
{
    public function scoreAcessControll($query)
    {
        $authUser = \Auth::user();
        if ($authUser->id === 1) {
            return $query;
        } elseif ($this instanceof User) {
            return $query->where("id", $authUser->id);
        } else if (isset($this->fillable['user_id'])) {
            return $query->where("user_id", $authUser->id);
        } else if (isset($this->fillable['env_id'])) {
            return $query->where("env_id", $authUser->getSitesIdsCache());
        } else {
            throw UserErrorException::unauthorizedAccess();
        }

    }
}