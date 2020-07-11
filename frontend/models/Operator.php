<?php
namespace frontend\models;

use Yii;
use yii\helpers\Url;

class Operator extends \common\models\Operator
{
	public function getViewUrl()
	{
		return Url::to(['operator/view', 'id' => $this->id, 'slug' => $this->slug]);
	}
}