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
    public $widget_id = 'dashboardtaskwidget';

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

        $this->registerClientScript();
        return $this->render('dashboardtaskwidget.tpl', [
            'models' => $models, 
            'myTaskModels' => $myTaskModels,
            'widget_id' => $this->widget_id,
            'ref' => Url::to(['site/index', '#' => 'dashboardtaskwidget'])
        ]);
    }

    protected function registerClientScript()
    {
        $js = [];
        $view = $this->getView();
        $js[] = "$('#" . $this->widget_id . "').find('.task-change-status').ajax_action({
            confirm: true,
  callback: function(element, data) {
    console.log(data);
    $('#" . $this->widget_id . "').find('.mt-comment-status').text($(element).text());
    $('#" . $this->widget_id . "').find('.progress-bar').css('width', data.percent + '%');
  },
  error: function(element, errors) {
    console.log(errors);
    alert(errors);
  }
});";
        $view->registerJs(implode("\n", $js));
        $view->registerCssFile('vendor/assets/global/plugins/bootstrap-select/css/bootstrap-select.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
        $view->registerCssFile('vendor/assets/apps/css/todo.min.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
        $view->registerJsFile('vendor/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
        $view->registerJsFile('vendor/assets/pages/scripts/components-bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
    }
}