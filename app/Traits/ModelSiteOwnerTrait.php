<?php

namespace App\Traits;

trait ModelSiteOwnerTrait
{
    public function scopeSiteOwner($query)
    {
        return $query->where("user_id", getSiteOwnerId());

    }

    public static function getOnlyIdByIdWithSiteCheck($id)
    {
        return self::siteOwner()->select("id")->whereId($id)->first();
    }

    public static function getByIdWithSiteCheck($id)
    {
        return self::siteOwner()->whereId($id)->first();
    }

    public static function getByUuidIdWithSiteCheck($uuid)
    {
        return self::siteOwner()->whereUuid($uuid)->first();
    }

    public static function getByIdWithSiteCheckOrFail($id)
    {

        return self::siteOwner()->whereId($id)->firstOrFail();
    }

    public static function getByUuidWithSiteCheckOrFail($uuid)
    {

        return self::siteOwner()->whereUuid($uuid)->first();
    }
}