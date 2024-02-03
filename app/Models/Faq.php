<?php

namespace App\Models;

use App\Enums\CacheExpiresInEnum;
use App\Enums\CacheKeysEnum;
use App\Enums\YesNoAsIntEnum;
use App\Traits\ModelAcessControllTrait;
use App\Traits\ModelSearchTrait;
use App\Traits\ModelSiteOwnerTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faq extends Model
{
    use ModelSearchTrait;
    use ModelAcessControllTrait;
    use ModelSiteOwnerTrait;
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'faqs';

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
    protected $fillable = ["id", 'title', 'description', 'order', 'show', 'user_id'];


    public static function getEnumFields()
    {
        return [
            'show' => [
                '0' => 'yes',
                '1' => 'no'
            ]
        ];
    }

    public static function createProductFaqRelations(Faq $faq)
    {
        $products = Faq::whereUserId($faq->user_id)->get();
        foreach ($products as $product) {
            $where = ['product_id' => $product->id, 'faq_id' => $faq->id];
            $productFaq = ProductFaq::where($where)->first();
            if (!isset($productFaq['id'])) {
                $productFaqModel = new ProductFaq();
                $productFaqModel->faq_id = $faq->id;
                $productFaqModel->product_id = $product->id;
                $productFaqModel->saveOrFail();
            }
        }

    }

    public static function createProductFaqRelationsByProduct(Product $product)
    {
        $faqs = Faq::whereUserId($product->user_id)->get();
        foreach ($faqs as $faq) {
            $where = ['product_id' => $product->id, 'faq_id' => $faq->id];
            $productFaq = ProductFaq::where($where)->first();
            if (!isset($productFaq['id'])) {
                $productFaqModel = new ProductFaq();
                $productFaqModel->faq_id = $faq->id;
                $productFaqModel->product_id = $product->id;
                $productFaqModel->saveOrFail();
            }
        }

    }

    public static function getAllActive($userId, $forceUpdate = false)
    {
        return getCacheOrCreate(CacheKeysEnum::getSIteFaqKey($userId), null, function () use ($userId) {
            return Faq::whereUserId($userId)->whereShow(YesNoAsIntEnum::Yes)->orderBy("order", "asc")->get();
        }, CacheExpiresInEnum::OneMonth, $forceUpdate);

    }

}
