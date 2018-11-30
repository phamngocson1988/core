<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Game model
 *
 * @property integer $id
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