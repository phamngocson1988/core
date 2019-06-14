<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class OrderImage extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_image}}';
    }
}