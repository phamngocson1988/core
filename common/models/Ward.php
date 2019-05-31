<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Ward extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%ward}}';
    }
}
