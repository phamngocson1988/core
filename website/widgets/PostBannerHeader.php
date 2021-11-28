<?php
namespace website\widgets;

use Yii;
use yii\base\Widget;

class PostBannerHeader extends Widget
{
    public function run()
    {
        return $this->render('post-banner-header');
    }

}