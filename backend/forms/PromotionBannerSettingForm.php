<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Image;

class PromotionBannerSettingForm extends Model
{
    public $images;

    public function rules()
    {
        return [
            [['images'], 'trim'],
        ];
    }

    public function getGalleryImages()
    {
        $imageIds = explode(",", $this->images);
        $imageIds = array_filter($imageIds);
        $images = Image::findAll($imageIds);
        return $images;
    }
}