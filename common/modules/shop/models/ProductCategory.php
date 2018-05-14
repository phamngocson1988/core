<?php
namespace common\modules\shop\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\Category;

/**
 * ProductCategory model
 *
 * @property integer $product_id
 * @property integer $category_id
 * @property string $is_main
 */
class ProductCategory extends ActiveRecord
{
	const MAIN_N = 'N';
    const MAIN_Y = 'Y';

	public static function tableName()
    {
        return '{{%product_category}}';
    }
}