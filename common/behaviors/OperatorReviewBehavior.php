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

    public function averageReviewRating() 
    {
        $owner = $this->owner; // Operator
        return $owner->averageStar() / 10;
    }

    public function averageReviewPercent() 
    {
        $owner = $this->owner; // Operator
        return $owner->averageStar() * 10;
    }

    public function countReview()
    {
        $owner = $this->owner; // Operator
    	return OperatorReview::find()->where(['operator_id' => $owner->id])->count();
    }

    public function countResponsedReview()
    {
        $owner = $this->owner; // Operator
        return OperatorReview::find()->where(['operator_id' => $owner->id])
        ->andWhere(['IS NOT', 'reply', null])
        ->count();
    }

    public function countUnResponsedReview()
    {
        $owner = $this->owner; // Operator
        return OperatorReview::find()->where(['operator_id' => $owner->id])
        ->andWhere(['IS', 'reply', null])
        ->count();
    }
}
