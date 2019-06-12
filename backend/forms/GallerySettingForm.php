<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

class GallerySettingForm extends Model
{
    public $title1;
    public $content1;
    public $link1;

    public $title2;
    public $content2;
    public $link2;

    public $title3;
    public $content3;
    public $link3;

    public $title4;
    public $content4;
    public $link4;

    public function rules()
    {
        return [
            [['title1', 'content1', 'link1'], 'trim'],
            [['title2', 'content2', 'link2'], 'trim'],
            [['title3', 'content3', 'link3'], 'trim'],
            [['title4', 'content4', 'link4'], 'trim'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title1' => 'Tiêu đề',
            'content1' => 'Nội dung',

            'title2' => 'Tiêu đề',
            'content2' => 'Nội dung',

            'title3' => 'Tiêu đề',
            'content3' => 'Nội dung',

            'title4' => 'Tiêu đề',
            'content4' => 'Nội dung',
        ];
    }
}