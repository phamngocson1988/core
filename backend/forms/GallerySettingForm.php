<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

class GallerySettingForm extends Model
{
    public $main_gallery[];
    public $gallery1[];

    public function rules()
    {
        return [
            [['main_gallery', 'gallery1'], 'trim'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'main_gallery' => Yii::t('app', 'contact_phone'),
            'gallery1' => Yii::t('app', 'contact_email'),
        ];
    }
}