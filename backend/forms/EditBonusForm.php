<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Bonus;
use backend\models\Operator;
use yii\helpers\ArrayHelper;

class EditBonusForm extends Model
{
    public $id;
    public $title;
    public $content;
    public $image_id;
    public $operator_id;
    public $status;

    protected $_bonus;

    public function rules()
    {
        return [
            [['id', 'title', 'content', 'status'], 'required'],
            ['id', 'validateBonus'],
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

    public function validateBonus($attribute, $params = []) 
    {
        $bonus = $this->getBonus();
        if (!$bonus) {
            $this->addError($attribute, Yii::t('app', 'bonus_is_not_exist'));
        }
    }
    public function update()
    {
        $bonus = $this->getBonus();
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

    public function loadData()
    {
        $bonus = $this->getBonus();
        $this->title = $bonus->title;
        $this->content = $bonus->content;
        $this->image_id = $bonus->image_id;
        $this->operator_id = $bonus->operator_id;
        $this->status = $bonus->status;
    }

    public function getBonus()
    {
        if (!$this->_bonus) {
            $this->_bonus = Bonus::findOne($this->id);
        }
        return $this->_bonus;
    }
}
