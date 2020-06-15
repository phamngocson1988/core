<?php
namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use frontend\models\Operator;
use frontend\models\Post;

class NewsByOperatorWidget extends Widget
{
    public $limit = 4;
    public $operator;
    public $operator_id;

    public function run()
    {
        $operator = $this->getOperator();
        if (!$operator) return '';

        $posts = Post::find()->where(['operator_id' => $operator->id])->limit($this->limit)->orderBy(['id' => SORT_DESC])->all();
        return $this->render('operator_news', [
            'posts' => $posts,
        ]);
    }

    public function getOperator()
    {
        if (!$this->operator) {
            $this->operator = Operator::findOne($this->operator_id);
        }
        return $this->operator;
    }
}