<?php
namespace frontend\models;

use Yii;

class Complain extends \common\models\Complain
{
	public function getIcon()
	{
		return sprintf("/img/complain/%s.png", $this->status);
	}
}