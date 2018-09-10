<?php
namespace backend\widgets;

use yii\base\Widget;
use backend\forms\FetchStaffForm;
use backend\modules\shop\forms\FetchProductForm;
use \Datetime;
use \DateInterval;

class DashboardStaffBirthdayWidget extends Widget
{
    public $within_next_days = 7;

    public function run()
    {
        $nextDays = $this->within_next_days;
        $condition = array();
        $date = new DateTime();
        $condition['birthday_from'] = $date->format('Y-m-d');
        $date->add(new DateInterval("P" . $nextDays . "D"));
        $condition['birthday_to'] = $date->format('Y-m-d');
        $fetchStaffForm = new FetchStaffForm($condition);
        $models = $fetchStaffForm->fetch();

        return $this->render('dashboardstaffbirthdaywidget.tpl', [
            'models' => $models, 
        ]);
    }
}