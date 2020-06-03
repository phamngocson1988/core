<?php
namespace common\behaviors;

use Yii;
use yii\behaviors\AttributeBehavior;
use common\models\OperatorReview;

class UserOperatorReviewBehavior extends AttributeBehavior
{
    public function isReview($operatorId) 
    {
        $owner = $this->owner; // User
        return OperatorReview::find()->where([
            'user_id' => $owner->id,
            'operator_id' => $operatorId
        ])->exists();
    }
}
