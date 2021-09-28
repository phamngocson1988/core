<?php
namespace backend\forms;

use Yii;
use common\forms\ActionForm;
use backend\models\User;
use backend\models\UserReseller;

class CreateResellerForm extends ActionForm
{
    public $user_id;
    public $task_code;
    protected $_user;

    public function rules()
    { 
        return [
            [['user_id', 'task_code'], 'required'],
            ['user_id', 'validateUser'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'task_code' => 'Mã đề xuất'
        ];
    }

    public function validateUser($attribute, $params) 
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->addError($attribute, 'Reseller không tồn tại');
        }
        if ($user->isReseller()) {
            return $this->addError($attribute, 'User đã là reseller');
        }
    }

    public function process()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = $this->getUser();
        $user->is_reseller = User::IS_RESELLER;
        $user->save(true, ['is_reseller']);

        $reseller = new UserReseller();
        $reseller->user_id = $user->id;
        $reseller->level = UserReseller::RESELLER_LEVEL_1;
        $reseller->task_code = $this->task_code;
        $reseller->save();
        return true;
    }
    public function getUser()
    {
        if (!$this->_user) {
            $this->_user = User::findOne($this->user_id);
        }
        return $this->_user;
    }
}
