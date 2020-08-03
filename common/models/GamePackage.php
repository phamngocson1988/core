<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;

class GamePackage extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%game_package}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'slugAttribute' => 'slug',
                'immutable' => true,
                'ensureUnique'=>true,
            ],
        ];
    }
}