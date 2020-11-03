<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;
use frontend\models\User;
use frontend\models\Complain;
use frontend\models\ComplainFollow;

class FollowComplainForm extends Model
{
    public $user_id;
    public $complain_id;

    protected $_user;
    protected $_complain;

    public function rules()
    {
        return [
            [['user_id', 'complain_id'], 'required'],
            ['user_id', 'validateUser'],
            ['complain_id', 'validateComplain'],
        ];
    }

    public function validateUser($attribute, $params = [])
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addError($attribute, Yii::t('app', 'User is not exist'));
        }
    }

    public function validateComplain($attribute, $params = [])
    {
        $complain = $this->getComplain();
        if (!$complain) {
            $this->addError($attribute, Yii::t('app', 'Complain is not exist'));
        }
    }

    public function follow()
    {
        $follow = ComplainFollow::find()->where([
            'user_id' => $this->user_id,
            'complain_id' => $this->complain_id
        ])->exists();
        if (!$follow) {
            $follow = new ComplainFollow();
            $follow->user_id = $this->user_id;
            $follow->complain_id = $this->complain_id;
            return $follow->save();
        }
        return true;
    }

    public function unfollow()
    {
        $follow = ComplainFollow::find()->where([
            'user_id' => $this->user_id,
            'complain_id' => $this->complain_id
        ])->one();
        if ($follow) {
            return $follow->delete();
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

    public function getComplain()
    {
        if (!$this->_complain) {
            $this->_complain = Complain::findOne($this->complain_id);
        }
        return $this->_complain;
    }

}
