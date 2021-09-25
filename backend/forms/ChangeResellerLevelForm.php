<?php
namespace backend\forms;

use Yii;
use common\forms\ActionForm;
use backend\models\User;
use backend\models\UserReseller;

class ChangeResellerLevelForm extends ActionForm
{
    public $user_id;
    public $task_code;
    public $level;
    protected $_user;

    public function rules()
    { 
        return [
            [['user_id', 'task_code', 'level'], 'required'],
            ['level', 'in', 'range' => ['up', 'down']],
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
        if (!$user->isReseller()) {
            return $this->addError($attribute, 'User không phải reseller');
        }
    }

    public function process()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = $this->getUser();
        $reseller = $user->reseller;
        $nextLevel = $this->level === 'up' ? $reseller->level + 1 : $reseller->level - 1;
        if (!in_array($nextLevel, [UserReseller::RESELLER_LEVEL_1, UserReseller::RESELLER_LEVEL_2, UserReseller::RESELLER_LEVEL_3])) {
            return true;
        }

        $currentTime = date('Y-m-d H:i:s');
        $reseller->old_level = $reseller->level;
        $reseller->level = $nextLevel;
        $reseller->task_code = $this->task_code;
        $reseller->level_updated_by = Yii::$app->user->id;
        $reseller->level_updated_at = $currentTime;
        $reseller->save();

        $user->old_reseller_level = $user->reseller_level;
        $user->reseller_level = $nextLevel;
        $user->reseller_updated_at = $currentTime;
        $user->save(false, ['reseller_level', 'old_reseller_level', 'reseller_updated_at']);

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
