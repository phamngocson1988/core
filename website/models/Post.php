<?php
namespace website\models;

use Yii;
use yii\db\ActiveQuery;

class Post extends \common\models\Post
{
	public static function find()
	{
		return new PostQuery(get_called_class());
	}

    public function getImageUrl($size = null, $default = '/images/post-item01.jpg')
    {
        $image = $this->image;
        if ($image) {
            return $image->getUrl($size);
        }
        return $default;
    }
}

class PostQuery extends ActiveQuery
{
    public function init()
    {
        $this->andOnCondition(['status' => Post::STATUS_VISIBLE]);
        parent::init();
    }
}
