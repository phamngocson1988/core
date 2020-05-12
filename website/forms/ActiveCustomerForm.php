<?php
namespace frontend\forms;

use yii\base\Model;
use common\models\User;

/**
 * ActiveCustomerForm form
 */
class ActiveCustomerForm extends Model
{
    public $id;
    public $auth_key;

	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'auth_key'], 'trim'],
            [['id', 'auth_key'], 'required'],
        ];
    }

    /**
     * Find user base on given id and auth_key
     * If user is exist, update its status to "active"
     *
     * @return <false|User>
     */
    public function confirm()
    {
    	if (!$this->validate()) {
            return false;
        }

    	$user = User::find()->where([
            'id' => $this->id,
            'auth_key' => $this->auth_key,
            'status' => User::STATUS_INACTIVE,
        ])->one();

        if (!empty($user)) {
            $user->status = User::STATUS_ACTIVE;
            $user->save();
            return $user;
        }
        return false;
    }
}