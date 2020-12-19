<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\Tracking;

class DailyCronController extends Controller
{
	public $date;

	public function init() 
	{
		parent::init();
		$this->date = date('Y-m-d');
	}

    public function actionStartOrder()
    {
        $track = new Tracking();
        $form = new \console\forms\StartOrderForm();
        $count = $form->start();
        if ($count !== false) {
	        $track->description = sprintf("Run starting order success on %s with % orders", $this->date, $count);
        } else {
        	$error = $form->getFirstErrorMessage();
	        $track->description = sprintf("Run starting order failure on %s: %s", $this->date, $error);
        }
	    return $track->save();
    }

    public function actionRunBenefit()
    {
        $track = new Tracking();
        $form = new \console\forms\RunBenefitForm();
        $count = $form->run();
        if ($count !== false) {
	        $track->description = sprintf("Run benefit success on %s with %s orders", $this->date, $count);
        } else {
        	$error = $form->getFirstErrorMessage();
	        $track->description = sprintf("Run benefit failure on %s: %s", $this->date, $error);
        }
	    return $track->save();
    }
}