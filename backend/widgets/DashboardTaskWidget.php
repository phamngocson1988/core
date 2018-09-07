<?php
namespace backend\widgets;

use yii\base\Widget;
use common\models\Task;
use backend\forms\FetchTaskForm;
use yii\data\Pagination;
use yii\helpers\Url;

class DashboardTaskWidget extends Widget
{
    public $user_id;

	public function run()
    {
        $condition = [];
        $condition['status'] = [Task::STATUS_NEW, Task::STATUS_INPROGRESS];

        $form = new FetchTaskForm($condition);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();

        $myTaskCondition = [];
        $myTaskCondition['status'] = [Task::STATUS_NEW, Task::STATUS_INPROGRESS];
        $myTaskCondition['assignee'] = $this->user_id;
        $formMyTask = new FetchTaskForm($myTaskCondition);
        $myTaskCommand = $formMyTask->getCommand();
        $myTaskPages = new Pagination(['totalCount' => $myTaskCommand->count()]);
        $myTaskModels = $myTaskCommand->offset($myTaskPages->offset)
                            ->limit($myTaskPages->limit)
                            ->all();

        $this->view->registerCssFile('vendor/assets/global/plugins/bootstrap-select/css/bootstrap-select.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
        $this->view->registerCssFile('vendor/assets/apps/css/todo.min.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
        $this->view->registerJsFile('vendor/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
        $this->view->registerJsFile('vendor/assets/pages/scripts/components-bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
        return $this->render('dashboardtaskwidget.tpl', [
            'models' => $models, 
            'myTaskModels' => $myTaskModels,
            'ref' => Url::to(['site/index', '#' => 'dashboardtaskwidget'])
        ]);
    }
}