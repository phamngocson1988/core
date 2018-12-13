<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * ProductOption model
 *
 * @property integer $id
 * @property string $title
 * @property integer $product_id
 * @property integer $price
 * @property integer $gems
 * @property integer $sale_price
 * @property string $status
 * @property integer $position
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 * @property integer $deleted_at
 * @property integer $deleted_by
 */
class ProductOption extends ActiveRecord
{
	const STATUS_INVISIBLE = 'N';
    const STATUS_VISIBLE = 'Y';
    const STATUS_DELETE = 'D';

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => date('Y-m-d H:i:s')
            ],
        ];
    }

	public static function tableName()
    {
        return '{{%product_option}}';
    }

    public function delete()
    {
        $this->status = self::STATUS_DELETE;
        $this->save();
    }
}