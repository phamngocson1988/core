<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

class GallerySettingForm extends Model
{
    public $title1;
    public $content1;
    public $link1;
    public $href1;

    public $title2;
    public $content2;
    public $link2;
    public $href2;

    public $title3;
    public $content3;
    public $link3;
    public $href3;

    public $title4;
    public $content4;
    public $link4;
    public $href4;

    public $title5;
    public $content5;
    public $link5;
    public $href5;

    public $title6;
    public $content6;
    public $link6;
    public $href6;

    public $title7;
    public $content7;
    public $link7;
    public $href7;

    public $title8;
    public $content8;
    public $link8;
    public $href8;

    public function rules()
    {
        return [
            [['title1', 'content1', 'link1', 'href1'], 'trim'],
            [['title2', 'content2', 'link2', 'href2'], 'trim'],
            [['title3', 'content3', 'link3', 'href3'], 'trim'],
            [['title4', 'content4', 'link4', 'href4'], 'trim'],

            [['title5', 'content5', 'link5', 'href5'], 'trim'],
            [['title6', 'content6', 'link6', 'href6'], 'trim'],
            [['title7', 'content7', 'link7', 'href7'], 'trim'],
            [['title8', 'content8', 'link8', 'href8'], 'trim'],
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
            'href1' => 'Link',

            'title2' => 'Tiêu đề',
            'content2' => 'Nội dung',
            'href2' => 'Link',

            'title3' => 'Tiêu đề',
            'content3' => 'Nội dung',
            'href3' => 'Link',

            'title4' => 'Tiêu đề',
            'content4' => 'Nội dung',
            'href4' => 'Link',

            'title5' => 'Tiêu đề',
            'content5' => 'Nội dung',
            'href5' => 'Link',

            'title6' => 'Tiêu đề',
            'content6' => 'Nội dung',
            'href6' => 'Link',

            'title7' => 'Tiêu đề',
            'content7' => 'Nội dung',
            'href7' => 'Link',

            'title8' => 'Tiêu đề',
            'content8' => 'Nội dung',
            'href8' => 'Link',
        ];
    }
}