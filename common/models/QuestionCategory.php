<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;

class QuestionCategory extends ActiveRecord
{
	public static function tableName()
    {
        return '{{%question_category}}';
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

    public function rules()
    {
        return [
            [['title'], 'required'],
            [['description', 'icon_url'], 'trim'],
        ];
    }

    public function attributeLabels() { 

        return  [
            'title' => Yii::t('app', 'title'),
        ];
    }

    public function getQuestions() 
    {
        return $this->hasMany(Question::className(), ['category_id' => 'id']);
    }
}