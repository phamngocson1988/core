<?php
namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;

class UserRefer extends \common\models\UserRefer
{
	const SCENARIO_CREATE = 'SCENARIO_CREATE';

	public function scenarios()
    {
        return [
        	self::SCENARIO_CREATE => ['user_id', 'email', 'name']
       	];
    }

    public function rules()
    {
        return [
        	[['user_id', 'email', 'name'], 'trim'],
        	[['user_id', 'email', 'name'], 'required'],
        ];
    }
}