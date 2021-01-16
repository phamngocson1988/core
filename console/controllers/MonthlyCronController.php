<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\Tracking;

class MonthlyCronController extends Controller
{
    public function actionCleanOrderImage()
    {
        $track = new Tracking();
        $track->description = sprintf("Run CleanOrderImageForm at %s", date('Y-m-d H:i:s'));
        $form = new \console\forms\CleanOrderImageForm(['durationMonth' => 3]);
        $track->save();
        return $form->run();
    }
}