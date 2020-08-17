<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class PostCategory extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%post_category}}';
    }
}