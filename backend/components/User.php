<?php
namespace backend\components;

use Yii;

class User extends \yii\web\User
{
	public function isRole($name)
	{
		$auth = $this->getAuthManager();
		$roles = $auth->getRolesByUser($this->id);
		$roleNames = array_keys($roles);
		$name = (array)$name;
		$intersect = array_intersect($roleNames, $name);
		return count($intersect);
	}
}