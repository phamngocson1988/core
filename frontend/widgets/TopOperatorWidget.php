<?php
namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use frontend\models\Operator;

class TopOperatorWidget extends Widget
{
    public $limit = 10;
    public function run()
    {
        $topOperators = Operator::find()->limit($this->limit)->orderBy(['id' => SORT_DESC])->all();
        return $this->render('top_operator', [
            'topOperators' => $topOperators,
        ]);
    }
}