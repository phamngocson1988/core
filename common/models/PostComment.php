<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\User;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * Post model
 */
class PostComment extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%post_comment}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => false
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => date('Y-m-d H:i:s'),
            ],
        ];
    }

    public function getCreatedAt($format = false, $default = 'F j, Y, g:i a')
    {
        if ($format == true) {
            return date($default, strtotime($this->created_at));
        }
        return $this->created_at;
    }


    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getCreatorName()
    {
        $creator = $this->creator;
        if ($creator) {
            return $creator->getName();
        }
        return '';
    }
}
