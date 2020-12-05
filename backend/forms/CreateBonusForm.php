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
    public $currency;
    public $bonus_type;
    public $minimum_deposit;
    public $minimum_deposit_value;
    public $wagering_requirement;
    public $cashable;
    public $language;
    public $link;

    public function init()
    {
        $languages = array_keys(Yii::$app->params['languages']);
        if (!in_array($this->language, $languages)) {
            $this->language = reset($languages);
        }
    }

    public function rules()
    {
        return [
            [['title', 'status'], 'required'],
            [['image_id', 'operator_id', 'currency', 'bonus_type', 'minimum_deposit', 'minimum_deposit_value', 'wagering_requirement', 'cashable', 'link'], 'safe'],
            ['language', 'required'],
            ['language', 'in', 'range' => array_keys(Yii::$app->params['languages'])],
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
            'language' => Yii::t('app', 'language'),
            'link' => Yii::t('app', 'link'),
        ];
    }
    
    public function create()
    {
        $bonus = new Bonus();
        $bonus->title = $this->title;
        $bonus->language = $this->language;
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
        $operators = Operator::find()
        ->where(['language' => $this->language])
        ->select(['id', 'name'])->all();
        return ArrayHelper::map($operators, 'id', 'name');
    }

    public function fetchStatus()
    {
        return Bonus::getStatusList();
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

    public function fetchLanguages()
    {
        return ArrayHelper::map(Yii::$app->params['languages'], 'code', 'title');
    }
}
