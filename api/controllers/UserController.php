<?php
namespace api\controllers;

use yii\rest\ActiveController;

class UserController extends ActiveController
{
	public $modelClass = 'api\models\User';
}