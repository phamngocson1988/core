<?php
namespace backend\widgets;

use yii\base\Widget;
use backend\forms\FetchCustomerForm;
use backend\forms\FetchGameForm;

class DashboardStatisticsWidget extends Widget
{
    public function run()
    {
        $fetchCustomerForm = new FetchCustomerForm();
        $fetchCustomerCommand = $fetchCustomerForm->getCommand();
        $countCustomer = $fetchCustomerCommand->count();

        $fetchProductForm = new FetchGameForm();
        $fetchProductCommand = $fetchProductForm->getCommand();
        $countProduct = $fetchProductCommand->count();

        return $this->render('dashboardstatisticswidget.tpl', [
            'countCustomer' => $countCustomer, 
            'countProduct' => $countProduct
        ]);
    }
}