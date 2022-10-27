<?php
namespace website\components\filters;

use Yii;
use yii\filters\AccessControl;

class BlockIpAccessControl extends AccessControl 
{
    public $denyRedirect = ['site/request-access'];

    protected function denyAccess($user) 
    {
        if ($this->denyRedirect) {
          return Yii::$app->response->redirect($this->denyRedirect);
        } else {
          return parent::denyAccess($user);
        }
    }

    public function init()
    {
        parent::init();
        $this->rules[] = Yii::createObject(array_merge($this->ruleConfig, [
            'allow' => true,
            'matchCallback' => function ($rule, $action) {
                $clientIp = Yii::$app->request->userIP;
                $checker = new \website\forms\BlockIpAccessForm(['ip' => $clientIp]);
                return $checker->run();
            }
        ]));
    }
}
