<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class JobHandler extends ActiveRecord
{
	public static function tableName()
    {
        return '{{%job_handler}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => date('Y-m-d H:i:s')
            ],
        ];
    }

    public function run()
    {
        Yii::$app->queueManual->executeSingleJob($this->job_id);
        $this->delete();
    }
}