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
    public $currency;
    public $bonus_type;
    public $minimum_deposit;
    public $minimum_deposit_value;
    public $wagering_requirement;
    public $cashable;
    public $link;

    protected $_bonus;

    public function rules()
    {
        return [
            [['id', 'title', 'status'], 'required'],
            ['id', 'validateBonus'],
            [['image_id', 'operator_id', 'currency', 'bonus_type', 'minimum_deposit', 'minimum_deposit_value', 'wagering_requirement', 'cashable', 'link'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('app', 'title'),
            'content' => Yii::t('app', 'content'),
            'operator' => Yii::t('app', 'operator'),
            'status' => Yii::t('app', 'status'),
            'currency' => Yii::t('app', 'currency'),
            'bonus_type' => Yii::t('app', 'bonus_type'),
            'minimum_deposit' => Yii::t('app', 'minimum_deposit'),
            'minimum_deposit_value' => Yii::t('app', 'minimum_deposit_value'),
            'wagering_requirement' => Yii::t('app', 'wagering_requirement'),
            'cashable' => Yii::t('app', 'cashable'),
            'link' => Yii::t('app', 'link'),
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
        $bonus->currency = $this->currency;
        $bonus->bonus_type = $this->bonus_type;
        $bonus->minimum_deposit = $this->minimum_deposit;
        $bonus->minimum_deposit_value = $this->minimum_deposit_value;
        $bonus->wagering_requirement = $this->wagering_requirement;
        $bonus->cashable = $this->cashable;
        $bonus->link = $this->link;
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
        $this->currency = $bonus->currency;
        $this->bonus_type = $bonus->bonus_type;
        $this->minimum_deposit = $bonus->minimum_deposit;
        $this->minimum_deposit_value = $bonus->minimum_deposit_value;
        $this->wagering_requirement = $bonus->wagering_requirement;
        $this->cashable = $bonus->cashable;
        $this->link = $bonus->link;
    }

    public function getBonus()
    {
        if (!$this->_bonus) {
            $this->_bonus = Bonus::findOne($this->id);
        }
        return $this->_bonus;
    }

    public function fetchCurrency()
    {
        return ArrayHelper::getValue(Yii::$app->params, 'currency', []);
    }

    public function fetchType()
    {
        return Bonus::getTypeList();
    }

    public function fetchCash()
    {
        return [
            0 => 'No',
            1 => 'Yes'
        ];
    }
}
