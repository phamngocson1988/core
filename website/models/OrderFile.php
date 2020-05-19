<?php
namespace website\models;

use Yii;

class OrderFile extends \common\models\OrderFile
{
    public function getUrl()
    {
        $file = $this->file;
        if (!$file) return '/images/no-img.png';
        return $file->getUrl();
    }
}