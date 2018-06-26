<?php
namespace frontend\modules\shop;

use Yii;

class Module extends \yii\base\Module
{
    public $cart;
    
    public function init()
    {
        parent::init();
        \Yii::configure($this, require __DIR__ . '/config/main.php');
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['module.shop*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@frontend/modules/shop/messages',
            'fileMap' => [
                'module.shop' => 'app.php',
            ],
        ];
    }
}