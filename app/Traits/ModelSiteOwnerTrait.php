<?php

namespace App\Traits;

trait ModelSiteOwnerTrait
{
    public function scopeSiteOwner($query)
    {
        return $query->where("user_id", getSiteOwner());

    }

    public static function getByIdWithSiteCheck($id)
    {
        return self::siteOwner()->whereId($id)->first();
    }
}