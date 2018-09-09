<?php
namespace backend\widgets;

use yii\base\Widget;
use backend\forms\FetchCustomerForm;
use yii\data\Pagination;

class StaffBirthdayWidget extends Widget
{
    public function run()
    {
        $form = new FetchCustomerForm();
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();

        return $this->render('staffbirthdaywidget.tpl', [
            'models' => $countCustomer, 
        ]);
    }
}