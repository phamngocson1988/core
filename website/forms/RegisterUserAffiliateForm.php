<?php
namespace website\forms;

use Yii;
use yii\base\Model;
use website\models\UserAffiliate;

class RegisterUserAffiliateForm extends Model
{
    public $user_id;    
    public $preferred_im;
    public $im_account;
    public $company;
    public $channel;
    public $channel_type;

    public function rules()
    {
        return [
            [['user_id', 'preferred_im', 'im_account', 'company', 'channel', 'channel_type'], 'required'],
            ['user_id', 'validateUser']
        ];
    }

    public function validateUser($attribute, $params = []) 
    {
        $request = UserAffiliate::find()->where(['user_id' => $this->user_id])->one();
        if ($request) {
            if ($request->isEnable()) {
                $this->addError($attribute, 'You have been an affiliate.');
            } else {
                $this->addError($attribute, 'You have sent a request to Kinggems. Please waiting for approval.');
            }
        }
    }

    public function register()
    {
        $model = new UserAffiliate();
        $model->user_id = $this->user_id;    
        $model->preferred_im = $this->preferred_im;
        $model->im_account = $this->im_account;
        $model->company = $this->company;
        $model->channel = $this->channel;
        $model->channel_type = $this->channel_type;
        $model->status = UserAffiliate::STATUS_DISABLE;
        return $model->save();
    }

    public function fetchPreferImList()
    {
        return UserAffiliate::preferImList();
    }

    public function fetchChannelTypeList()
    {
        return UserAffiliate::channelTypeList();
    }
}
