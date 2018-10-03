<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\Image;

/**
 * Product model
 *
 * @property integer $id
 * @property string $title
 * @property integer $game_id
 * @property integer $image_id
 * @property integer $price
 * @property integer $gems
 * @property integer $sale_price
 * @property string $sale_off_type
 * @property datetime $sale_off_from
 * @property datetime $sale_off_to
 * @property string $status
 * @property integer $position
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 * @property integer $deleted_at
 * @property integer $deleted_by
 */
class Product extends ActiveRecord
{
	const STATUS_INVISIBLE = 'N';
    const STATUS_VISIBLE = 'Y';
    const STATUS_DELETE = 'D';

    const SALE_TYPE = 'fix';
    const SALE_PERCENT = 'percent';

	public static function tableName()
    {
        return '{{%product}}';
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_VISIBLE => 'Visible',
            self::STATUS_INVISIBLE => 'Invisible',
            self::STATUS_DELETE => 'Deleted'
        ];
    }

    public static function getSaleTypeList()
    {
        return [
            self::SALE_TYPE => 'Fix',
            self::SALE_PERCENT => 'Percent',
        ];
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