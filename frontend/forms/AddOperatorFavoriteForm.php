<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use frontend\models\User;
use frontend\models\Operator;
use frontend\models\OperatorFavorite;

class AddOperatorFavoriteForm extends Model
{
    public $user_id;
    public $operator_id;

    protected $_user;
    protected $_operator;

    public function rules()
    {
        return [
            [['user_id', 'operator_id'], 'required'],
            ['user_id', 'validateUser'],
            ['operator_id', 'validateOperator'],
        ];
    }

    public function validateUser($attribute, $params = [])
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addError($attribute, Yii::t('app', 'User is not exist'));
        }
    }

    public function validateOperator($attribute, $params = [])
    {
        $operator = $this->getOperator();
        if (!$operator) {
            $this->addError($attribute, Yii::t('app', 'Operator is not exist'));
        }
    }

    public function add()
    {
        $favorite = OperatorFavorite::find()->where([
            'user_id' => $this->user_id,
            'operator_id' => $this->operator_id
        ])->exists();
        if (!$favorite) {
            $favorite = new OperatorFavorite();
            $favorite->user_id = $this->user_id;
            $favorite->operator_id = $this->operator_id;
            return $favorite->save();
        }
        return true;
    }

    public function getUser()
    {
        if (!$this->_user) {
            $this->_user = User::findOne($this->user_id);
        }
        return $this->_user;
    }

    public function getOperator()
    {
        if (!$this->_operator) {
            $this->_operator = Operator::findOne($this->operator_id);
        }
        return $this->_operator;
    }

}
