<?php
namespace common\behaviors;

use Yii;
use yii\behaviors\AttributeBehavior;
use common\models\OperatorReview;

class OperatorReviewBehavior extends AttributeBehavior
{
    public function averageStar() 
    {
        $owner = $this->owner; // Operator
        return OperatorReview::find()->where([
            'operator_id' => $owner->id
        ])->average('star');
    }
}
