<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\Category;
use common\models\Post;

/**
 * PostCategory model
 *
 * @property integer $post_id
 * @property integer $category_id
 * @property string $is_main
 */
class PostCategory extends ActiveRecord
{
	const MAIN_N = 'N';
    const MAIN_Y = 'Y';

	public static function tableName()
    {
        return '{{%post_category}}';
    }
}