<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\User;
use common\models\Image;
use yii\behaviors\TimestampBehavior;

/**
 * Product model
 *
 * @property integer $id
 * @property string $title
 * @property integer $game_id
 * @property integer $image_id
 * @property integer $position
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $deleted_by
 * @property integer $deleted_at
 * @property string $status
 */
class Product extends ActiveRecord
{
    const STATUS_INVISIBLE = 'N';
    const STATUS_VISIBLE = 'Y';
    const STATUS_DELETE = 'D';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product}}';
    }

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

    public function attributeLabels() 
    { 
        return  [
            'id' => Yii::t('app', 'id'),
            'title' => Yii::t('app', 'title'),
            'excerpt' => Yii::t('app', 'excerpt'),
            'game_id' => Yii::t('app', 'game_id'),
            'status' => Yii::t('app', 'status'),
            'price' => Yii::t('app', 'price'),
            'unit' => 'Đơn vị game',
            'image_id' => Yii::t('app', 'image'),
        ];
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_INVISIBLE => Yii::t('app', 'inactive'),
            self::STATUS_VISIBLE => Yii::t('app', 'active'),
            self::STATUS_DELETE => Yii::t('app', 'deleted'),
        ];
    }

    public function getGame() 
    {
        return $this->hasOne(Game::className(), ['id' => 'game_id']);
    }

    public function getImage() 
    {
        return $this->hasOne(Image::className(), ['id' => 'image_id']);
    }

    public function getImageUrl($size = null, $default = '/images/noimage.png')
    {
        $image = $this->image;
        if (!$image) {
            $image = $this->game->image;
        }
        if ($image) {
            return $image->getUrl($size);
        }
        return $default;
    }

    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getCreatorName()
    {
        $user = $this->creator;
        if ($user) {
            return $user->name;
        }
        return '';
    }

    public function getUnitName()
    {
        $game = $this->game;
        return $game->unit_name;
    }

    public function isVisible()
    {
        return $this->status === self::STATUS_VISIBLE;
    }

    public function isDisable()
    {
        return $this->status === self::STATUS_INVISIBLE;
    }

    public function isDeleted()
    {
        return $this->status === self::STATUS_DELETE;
    }
}
