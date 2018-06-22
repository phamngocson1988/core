<?php
namespace frontend\forms;

use yii\base\Model;
use common\models\Customer;

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
     * @return <false|Customer>
     */
    public function confirm()
    {
    	if (!$this->validate()) {
            return false;
        }

    	$user = Customer::find()->where([
            'id' => $this->id,
            'auth_key' => $this->auth_key,
            'status' => Customer::STATUS_INACTIVE,
        ])->one();

        if (!empty($user)) {
            $user->status = Customer::STATUS_ACTIVE;
            $user->save();
            return $user;
        }
        return false;
    }
}