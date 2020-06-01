<?php
namespace common\behaviors;

use Yii;
use yii\behaviors\AttributeBehavior;
use common\models\OperatorFavorite;

class UserOperatorFavoriteBehavior extends AttributeBehavior
{
    public function isOperatorFavorite($operatorId) 
    {
        $owner = $this->owner; // User
        return OperatorFavorite::find()->where([
            'user_id' => $owner->id,
            'operator_id' => $operatorId
        ])->exists();
    }
}
