<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Bonus;
use backend\models\Operator;
use yii\helpers\ArrayHelper;

class CreateBonusForm extends Model
{
    public $title;
    public $content;
    public $image_id;
    public $operator_id;
    public $status;

    public function rules()
    {
        return [
            [['title', 'content', 'status'], 'required'],
            [['image_id', 'operator_id'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('app', 'title'),
            'content' => Yii::t('app', 'content'),
            'operator' => Yii::t('app', 'operator'),
            'status' => Yii::t('app', 'status'),
        ];
    }
    
    public function create()
    {
        $bonus = new Bonus();
        $bonus->title = $this->title;
        $bonus->content = $this->content;
        $bonus->image_id = $this->image_id;
        $bonus->operator_id = $this->operator_id;
        $bonus->status = $this->status;
        return $bonus->save();
    }

    public function fetchOperator()
    {
        $operators = Operator::find()->select(['id', 'name'])->all();
        return ArrayHelper::map($operators, 'id', 'name');
    }

    public function fetchStatus()
    {
        return Bonus::getStatusList();
    }
}
