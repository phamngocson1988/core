<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;
use common\behaviors\ForumCategoryBehavior;

class ForumCategory extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%forum_category}}';
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
            'topic' => ForumCategoryBehavior::className(),
        ];
    }
}