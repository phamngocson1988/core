<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class ComplainFollow extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%complain_follow}}';
    }
}