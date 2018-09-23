<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

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
class ProductPackage extends ActiveRecord
{
	const STATUS_INVISIBLE = 'N';
    const STATUS_VISIBLE = 'Y';
    const STATUS_DELETE = 'D';

	public static function tableName()
    {
        return '{{%product}}';
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_INVISIBLE => 'Invisible',
            self::STATUS_VISIBLE => 'Visible',
            self::STATUS_DELETE => 'Deleted'
        ];
    }
}