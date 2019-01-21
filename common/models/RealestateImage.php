<?php

namespace common\models;

use Yii;

class RealestateImage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%realestate_image}}';
    }
}
