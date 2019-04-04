<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\Image;

/**
 * Game model
 *
 * @property integer $id
 * @property integer $game_id
 * @property integer $image_id
 */
class GameImage extends ActiveRecord
{
	public static function tableName()
    {
        return '{{%game_image}}';
    }

    public function getImage() 
    {
        return $this->hasOne(Image::className(), ['id' => 'image_id']);
    }

    public function getImageUrl($size = null, $default = '/images/noimage.png')
    {
        $image = $this->image;
        if ($image) {
            return $image->getUrl($size);
        }
        return $default;
    }
}