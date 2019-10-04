<?php
use yii\widgets\Pjax;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use frontend\models\Order;
use dosamigos\datepicker\DatePicker;

?>
<section class="page-title">
  <div class="container">
    <div class="row">
      <div class="col col-sm-12">
        <div class="page-title-content text-center">
          <img src="/images/text-your-account.png" alt="">
        </div>
        <div class="page-title-sub">
          <p>Manage your account</p>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="profile-page">
  <div class="container-fluid">
    <div class="row">
      <?php require_once(Yii::$app->basePath . '/views/user/_left_menu.php');?>
      <div class="wrap-profile-right col col-lg-8 col-md-9 col-sm-12 col-12">
        <div class="profile-list">
          <div class="top-profile-list">
            <div class="left-top-profile-list">
              <span class="number-page font-weight-bold"><?=$pages->offset + 1;?> - <?=min($pages->offset + $pages->limit, $pages->totalCount);?></span>
              <span class="text-page">of <?=number_format($pages->totalCount);?> Orders</span>
            </div>
            <div class="right-top-profile-list">
              <?=LinkPager::widget([
                'pagination' => $pages, 
                'maxButtonCount' => 1, 
                'hideOnSinglePage' => false,
                'linkOptions' => ['class' => 'page-link'],
                'pageCssClass' => 'page-item',
              ]);?>
              <?php $formOpt = ($showFilter) ? [] : ['style' => 'display:none'];?>
              <?php $form = ActiveForm::begin(['method' => 'get', 'options' => $formOpt]); ?>
              <?= $form->field($filterForm, 'start_date', [
                'labelOptions' => ['class' => 'col-md-4 control-label'],
                // 'template' => '{label}<div class="col-md-col-md-4">{input}{hint}{error}</div>'
              ])->widget(DatePicker::className(), [
                'inline' => false, 
                'template' => '<div class="input-group date" data-provide="datepicker">{input}<div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div></div>',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
              ]);?>
              <?= $form->field($filterForm, 'end_date', [
                'labelOptions' => ['class' => 'col-md-4 control-label'],
                // 'template' => '{label}<div class="col-md-col-md-4">{input}{hint}{error}</div>'
              ])->widget(DatePicker::className(), [
                'inline' => false, 
                'template' => '<div class="input-group date" data-provide="datepicker">{input}<div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div></div>',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
              ]);?>
              <?= $form->field($filterForm, 'status', [
                'inputOptions' => ['class' => 'form-control', 'name' => 'status', 'id' => 'status']
              ])->dropDownList($filterForm->fetchStatusList(), ['prompt' => 'All']);?>
              <?= $form->field($filterForm, 'status', [
                'inputOptions' => ['class' => 'form-control', 'name' => 'status', 'id' => 'status']
              ])->textInput();?>
              <?php $form = ActiveForm::end(); ?>
            </div>
          </div>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th scope="col">No</th>
                <th scope="col">Game</th>
                <th scope="col">Amount</th>
                <th scope="col">No. of Packages</th>
                <th scope="col">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="5">No data found</td></tr>
              <?php endif;?>
              <?php foreach ($models as $model) :?>
              <tr>
                <td>
                  <a href="<?=Url::to(['user/detail', 'id' => $model->id]);?>" data-pjax="0" class="normal-link" style="display:block; clear: left; line-height: 10px;"><?=$model->id;?></a>
                  <i style="font-size:13px; color:#CCC"><?=$model->created_at;?></i>
                </td>
                <td><?=$model->game_title;?></td>
                <td>
                  $<?=number_format($model->total_price);?>
                  <?php if (!in_array($model->currency, ['USD', 'KINGGEMS'])) : ?>
                  <i style="font-size:13px; color: #CCC"><?=$model->currency;?>/<?=number_format($model->total_price_by_currency, 1);?></i>
                  <?php endif; ?>
                </td>
                <td><?=number_format($model->quantity);?></td>
                <td><?=$model->getStatusLabel();?></td>
              </tr>
              <?php endforeach;?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="2">Total:</td>
                <td>$<?=number_format($filterForm->getCommand()->sum('total_price'), 1);?></td>
                <td><?=number_format($filterForm->getCommand()->sum('quantity'), 1);?></td>
                <td></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
$script = <<< JS
$('#status').on('change', function(){
  $(this).closest('form').submit();
})
JS;
$this->registerJs($script);
?>
