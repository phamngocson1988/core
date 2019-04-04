<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

class GallerySettingForm extends Model
{
    public $main_gallery = [];

    public function init()
    {
        parent::init();
        $this->main_gallery = array_filter($this->main_gallery);
    }

    public function rules()
    {
        return [
            [['main_gallery'], 'trim'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'main_gallery' => Yii::t('app', 'main_gallery'),
            // 'gallery1' => Yii::t('app', 'gallery1'),
        ];
    }
}