<?php
namespace common\behaviors;

use yii\behaviors\AttributeBehavior;
use common\models\GameReseller;

class UserResellerBehavior extends AttributeBehavior
{
    public function getGameResellers()
    {
        $owner = $this->owner;
        return $owner->hasMany(GameReseller::className(), ['reseller_id' => 'id']);
    }

    public function removeGameResellers()
    {
        $owner = $this->owner;
        GameReseller::deleteAll(['reseller_id' => $owner->id]);
    }
}
