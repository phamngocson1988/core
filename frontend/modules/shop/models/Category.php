<?php
namespace frontend\modules\shop\models;

use common\modules\models\Category as BaseCategory;
use yii\helpers\Url;

class Category extends BaseCategory
{
    public function getReadUrl($scheme = false)
    {
        return Url::to(["/shop/category/index", 'id' => $this->id, 'slug' => $this->slug], $scheme);
    }
}