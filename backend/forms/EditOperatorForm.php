<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\User;
use backend\models\Operator;
use yii\helpers\ArrayHelper;

class EditOperatorForm extends Model
{
    public $id;
	public $name;
    public $main_url;

    protected $_operator;

    public function rules()
    {
        return [
            ['id', 'required'],
            ['id', 'validateOperator'],

            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 255],

            ['main_url', 'trim'],
            ['main_url', 'string', 'max' => 255],

        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'operator_name'),
            'main_url' => Yii::t('app', 'operator_main_url'),
        ];
    }

    public function validateOperator($attribute, $pararms = [])
    {
        $operator = $this->getOperator();
        if (!$operator) {
            $this->addError($attribute, Yii::t('app', 'operator_is_not_exist'));
        }
    }

    public function getOperator()
    {
        if (!$this->_operator) {
            $this->_operator = Operator::findOne($this->id);
        }
        return $this->_operator;
    }

    public function update()
    {
        $operator = $this->getOperator();
        $operator->name = $this->name;
        $operator->main_url = $this->main_url;
        return $operator->save();
    }

    public function loadData()
    {
        $operator = $this->getOperator();
        $this->name = $operator->name;
        $this->main_url = $operator->main_url;
    }

}
