<?php
namespace common\behaviors;

use Yii;
use yii\behaviors\AttributeBehavior;
use common\models\Mail;

class UserMailBehavior extends AttributeBehavior
{
    public function countEmail()
    {
        $owner = $this->owner; // User
        return Mail::find()->where(['created_by' => $owner->id])->count();
    }

    public function getLimitEmail()
    {
        return 1000;
    }
}
