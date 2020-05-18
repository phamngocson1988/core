<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\User;
use backend\models\Operator;
use yii\helpers\ArrayHelper;

class CreateOperatorForm extends Model
{
	public $name;
    public $main_url;
    public $user_id;

    public $_manager;
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 255],

            ['main_url', 'trim'],
            ['main_url', 'string', 'max' => 255],

            ['user_id', 'safe'],
            ['user_id', 'validateManager'],
        ];
    }

    public function validateManager($attribute, $params = [])
    {
        $user = $this->getManager();
        if ($user && $user->hasOperator()) {
            $this->addError($attribute, Yii::t('app', 'user_is_belong_to_other_operator'));
        }   
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'operator_name'),
            'main_url' => Yii::t('app', 'operator_main_url'),
            'user_id' => Yii::t('app', 'operator_admin'),
        ];
    }

    public function create()
    {
        $operator = new Operator();
        $operator->name = $this->name;
        $operator->main_url = $this->main_url;

        $operator->on(Operator::EVENT_AFTER_INSERT, function ($event) {
            $operator = $event->sender; // Operator
            $manager = $event->data;
            if ($manager) {
                $manager->operator_id = $operator->id;
                $manager->save();
            }
        }, $this->getManager());

        return $operator->save();
    }

    public function fetchUsers()
    {
        $auth = Yii::$app->authManager;
        $userIds = $auth->getUserIdsByRole('admin');
        $users = User::find()
        ->where(['in', 'id', $userIds])
        ->andWhere(['IS', 'operator_id', null])
        ->select(['id', 'email'])
        ->all();
        return ArrayHelper::map($users, 'id', 'email');
    }

    public function getManager()
    {
        if (!$this->_manager && $this->user_id) {
            $this->_manager = User::findOne($this->user_id);
        }
        return $this->_manager;
    }
}
