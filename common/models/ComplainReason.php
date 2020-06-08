<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class ComplainReason extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%complain_reason}}';
    }

}