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
    public $type1;

    public $title2;
    public $content2;
    public $link2;
    public $href2;
    public $type2;

    public $title3;
    public $content3;
    public $link3;
    public $href3;
    public $type3;

    public $title4;
    public $content4;
    public $link4;
    public $href4;
    public $type4;

    public $title5;
    public $content5;
    public $link5;
    public $href5;
    public $type5;

    public $title6;
    public $content6;
    public $link6;
    public $href6;
    public $type6;

    public $title7;
    public $content7;
    public $link7;
    public $href7;
    public $type7;

    public $title8;
    public $content8;
    public $link8;
    public $href8;
    public $type8;

    public function rules()
    {
        return [
            [['title1', 'content1', 'link1', 'href1', 'type1'], 'trim'],
            [['title2', 'content2', 'link2', 'href2', 'type2'], 'trim'],
            [['title3', 'content3', 'link3', 'href3', 'type3'], 'trim'],
            [['title4', 'content4', 'link4', 'href4', 'type4'], 'trim'],
            [['title5', 'content5', 'link5', 'href5', 'type5'], 'trim'],
            [['title6', 'content6', 'link6', 'href6', 'type6'], 'trim'],
            [['title7', 'content7', 'link7', 'href7', 'type7'], 'trim'],
            [['title8', 'content8', 'link8', 'href8', 'type8'], 'trim'],
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
            'type1' => 'Loại',

            'title2' => 'Tiêu đề',
            'content2' => 'Nội dung',
            'href2' => 'Link',
            'type2' => 'Loại',

            'title3' => 'Tiêu đề',
            'content3' => 'Nội dung',
            'href3' => 'Link',
            'type3' => 'Loại',

            'title4' => 'Tiêu đề',
            'content4' => 'Nội dung',
            'href4' => 'Link',
            'type4' => 'Loại',

            'title5' => 'Tiêu đề',
            'content5' => 'Nội dung',
            'href5' => 'Link',
            'type5' => 'Loại',

            'title6' => 'Tiêu đề',
            'content6' => 'Nội dung',
            'href6' => 'Link',
            'type6' => 'Loại',

            'title7' => 'Tiêu đề',
            'content7' => 'Nội dung',
            'href7' => 'Link',
            'type7' => 'Loại',

            'title8' => 'Tiêu đề',
            'content8' => 'Nội dung',
            'href8' => 'Link',
            'type8' => 'Loại',
        ];
    }

    public function fetchTypeList() 
    {
        return [
            'image' => 'Hình ảnh',
            'youtube' => 'Youtube',
            'mp4' => 'Video mp4'
        ];
    }
}