<?php
namespace common\modules\shop\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Product model
 *
 * @property integer $product_id
 * @property integer $image_id
 */
class ProductImage extends ActiveRecord
{
	public static function tableName()
    {
        return '{{%product_image}}';
    }
}