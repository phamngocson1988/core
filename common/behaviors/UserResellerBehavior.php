<?php
namespace common\behaviors;

use Yii;
use yii\behaviors\AttributeBehavior;
use common\models\GameReseller;
use common\models\UserReseller;

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

    public function createReseller()
    {
        $owner = $this->owner;
        $reseller = new UserReseller();
        $reseller->user_id = $owner->id;
        $reseller->level = UserReseller::RESELLER_LEVEL_1;
        return $reseller->save();
    }

    public function deleteReseller()
    {
        $owner = $this->owner;
        $reseller = $owner->reseller;
        if ($reseller) return $reseller->delete();
        return false;
    }

    public function assignManager($id)
    {
        $owner = $this->owner;
        $reseller = $owner->reseller;
        if ($reseller) {
            $reseller->manager_id = $id;
            return $reseller->save();
        }
        return false;
    }

    public function upgrade($level = null)
    {
        $owner = $this->owner;
        $reseller = $owner->reseller;
        if ($reseller) {
            if (!$level) {
                $level = in_array($reseller->level + 1, [UserReseller::RESELLER_LEVEL_1, UserReseller::RESELLER_LEVEL_2, UserReseller::RESELLER_LEVEL_3]) ? $reseller->level + 1 : $reseller->level;
            }
            $reseller->level = $level;
            return $reseller->save();
        }
        return false;
    }

    public function downgrade($level = null)
    {
        $owner = $this->owner;
        $reseller = $owner->reseller;
        if ($reseller) {
            if (!$level) {
                $level = in_array($reseller->level - 1, [UserReseller::RESELLER_LEVEL_1, UserReseller::RESELLER_LEVEL_2, UserReseller::RESELLER_LEVEL_3]) ? $reseller->level - 1 : $reseller->level;
            }
            $reseller->level = $level;
            return $reseller->save();
        }
        return false;
    }
}
