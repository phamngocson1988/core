<?php
namespace supplier\models;

use Yii;
use common\models\User as CommonUser;
use supplier\behaviors\UserSupplierBehavior;

/**
 * @property string $password
 */
class User extends CommonUser
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['supplier'] = UserSupplierBehavior::className();
        return $behaviors;
    }
}