<?php

namespace App\Models;

use App\Environment;
use App\GanhosAfiliado;
use App\Traits\ModelAcessControllTrait;
use App\Traits\ModelSearchTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    use ModelSearchTrait;
    use ModelAcessControllTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'telephone', 'status', 'role', 'afiliado', 'pix', 'cpf', 'email', 'password'];

    protected $hidden = [
        'password', 'remember_token',
    ];


    public static function getEnumFields()
    {
        return [
            'status' => [
                '0' => 'inactive',
                '1' => 'active'
            ],
            'afiliado' => [
                '0' => 'yes',
                '1' => 'no'
            ]
        ];
    }

    public function totalGanhos()
    {
        if ($this->afiliado == false) {
            return 0;
        } else {
            $total = GanhosAfiliado::where('afiliado_id', '=', $this->id)->sum('valor');

            return $total;
        }
    }

    public function getUserSitesIds()
    {
        $ids = [];
        foreach (Environment::select("id")->where("user_id", $this->id)->get() as $env) {
            $ids[] = $env->id;
        }
        return $ids;
    }

    public function getUserProductIds()
    {
        $ids = [];
        foreach (Product::select("id")->where("user_id", $this->id)->get() as $p) {
            $ids[] = $p->id;
        }
        return $ids;
    }

    public function sitesCacheKey()
    {
        return 'sites_ids_' . $this->id;
    }

    public function sitesProductsKey()
    {
        return 'sites_products_ids_' . $this->id;
    }

    public function getSitesIdsCache()
    {
        $cacheKey = $this->sitesCacheKey();
        $instance = $this;
        return getCacheOrCreate($cacheKey, $instance, function ($instance) {
            return $instance->getUserSitesIds();
        }, 43800);
    }

    public function getProductIdsCache()
    {
        $cacheKey = $this->sitesProductsKey();
        $instance = $this;
        return getCacheOrCreate($cacheKey, $instance, function (self $instance) {
            return $instance->getUserProductIds();
        }, 43800);
    }
}
