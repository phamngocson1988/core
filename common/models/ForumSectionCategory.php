<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class ForumSectionCategory extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%forum_section_category}}';
    }
}