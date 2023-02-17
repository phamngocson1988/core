<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\components\helpers\FormatConverter;
use common\models\LeadTrackerSurvey;

$this->registerCssFile('@web/vendor/assets/global/plugins/datatables/datatables.min.css', ['depends' => [\backend\assets\AppAsset::className()]]);
$this->registerCssFile('@web/vendor/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css', ['depends' => [\backend\assets\AppAsset::className()]]);
$this->registerJsFile('@web/vendor/assets/global/plugins/datatables/datatables.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/vendor/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$saleForUser = $search->countSaleByUser();
$surveyAnswers = ArrayHelper::index($surveyAnswers, null, 'lead_tracker_id');
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home');?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Quản lý customer tracker</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Quản lý customer tracker</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Quản lý customer tracker</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <?php if (Yii::$app->user->can('admin')) : ?>
            <a role="button" class="btn green" href="<?=Url::to(['customer-tracker/report'])?>"><i class="fa fa-search"></i> Report</a>
            <a class="btn grey" href="<?=Url::to(['lead-tracker-survey/index']);?>">Quản lý câu hỏi</a>
            <a role="button" class="btn btn-warning" href="<?=Url::current(['mode' => 'export'])?>"><i class="fa fa-file-excel-o"></i> Export</a>
            <?php endif;?>
          </div>
        </div>
      </div>
      <div class="portlet-body">
      <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['customer-tracker/index']]);?>
        <div class="row margin-bottom-10">
            <?=$form->field($search, 'name', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'name']
            ])->textInput()->label('Name');?>
            <?=$form->field($search, 'country_code', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            ])->widget(kartik\select2\Select2::classname(), [
              'options' => ['class' => 'form-control', 'name' => 'country_code', 'prompt' => 'Select Country'],
              'data' => $search->listCountries(),
              'pluginOptions' => [
                'allowClear' => true
              ],
            ])->label('Nationality')?>
            <?=$form->field($search, 'phone', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'phone']
            ])->textInput()->label('Phone');?>
            <?=$form->field($search, 'email', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'email']
            ])->textInput()->label('Email');?>
            <?=$form->field($search, 'game_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'game_id']
            ])->widget(kartik\select2\Select2::classname(), [
              'data' => $search->fetchGames(),
              'options' => ['class' => 'form-control', 'placeholder' => '--Select--'],
              'pluginOptions' => [
                'allowClear' => true
              ],
            ])->label('Game')?>
            <?php if (Yii::$app->user->can('admin')):?>
            <?=$form->field($search, 'saler_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            ])->widget(kartik\select2\Select2::classname(), [
              'options' => ['class' => 'form-control', 'name' => 'saler_id', 'prompt' => 'Select Account Manager'],
              'data' => $search->fetchSalers(),
              'pluginOptions' => [
                'allowClear' => true
              ],
            ])->label('Account Manager')?>
            <?php endif;?>
            <?=$form->field($search, 'sale_growth', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'sale_growth']
            ])->dropDownList($search->getBooleanList(), ['prompt' => '--Select--'])->label('Sales Growth');?>
            <?=$form->field($search, 'product_growth', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'product_growth']
            ])->dropDownList($search->getBooleanList(), ['prompt' => '--Select--'])->label('Product Growth');?>

            <?=$form->field($search, 'is_loyalty', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'is_loyalty']
            ])->dropDownList($search->getBooleanList(), ['prompt' => '--Select--'])->label('Loyalty customer');?>

            <?=$form->field($search, 'is_dangerous', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'is_dangerous']
            ])->dropDownList($search->getBooleanList(), ['prompt' => '--Select--'])->label('Customer "in dangerous"');?>
			

            <div class="form-group col-md-4 col-lg-3">
              <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
                <i class="fa fa-check"></i> <?=Yii::t('app', 'search')?>
              </button>
            </div>
        </div>
        <?php ActiveForm::end()?>
        <table class="table table-striped table-bordered table-hover table-checkable" id="myTable">
          <thead style="font-weight: bold; color: white; background-color: #36c5d3">
            <tr>
              <th rowspan="3" class="center">Date</th>
              <th></th>
              <th></th>
              <th rowspan="2" colspan="7" class="center">General Information</th>
              <th colspan="12" class="center">Sales Performance</th>
              <th colspan="2" class="center" data-toggle="popover" data-placement="top" data-content="1st/2nd/3rd >= 105">Potential Customer</th>
              <th colspan="2" class="center" data-toggle="popover" data-placement="top" data-content="1st/2nd/3rd >= 150 AND %Result/ KPI >= 70%">Key Customer</th>
              <th rowspan="3" class="center">Tác vụ</th>
              <th colspan="9" class="center">Normal Customer</th>
              <th colspan="4" class="center">Potential Customer</th>
              <th colspan="6" class="center">Loyalty Customer</th>
              <th colspan="8" class="center">Dangerous Customer</th>
            </tr>
            <tr>
              <th></th>
              <th></th>
              <th colspan="5" class="center">Normal Customer</th>
              <th colspan="2" class="center">Growth rate</th>
              <th class="center">Growth speed</th>
              <th colspan="4" class="center">Development</th>
              <th rowspan="2" class="center">Evaluation</th>
              <th rowspan="2" class="center">1st date</th>
              <th rowspan="2" class="center">Evaluation</th>
              <th rowspan="2" class="center">1st date</th>              								
              <th colspan="9" class="center">Tạo lập mối quan hệ</th>
              <th colspan="4" class="center">Tìm hiểu chuyên sâu đối tác</th>
              <th colspan="6" class="center">Đánh giá của KH về Kinggems, lí do KH gắn bó</th>
              <th colspan="8" class="center">Điều gì khiến KH không hài lòng tại KingGems</th>
            </tr>
            <tr>
              <th>Status</th>
              <th>Monthly Status Customer</th>
              <th>Name</th>
              <th>Nationality</th>
              <th>Phone</th>
              <th>Email</th>
              <th>Channel</th>
              <th>Game chủ đạo</th>
              <th>Account Manager</th>
              <th>1st order date</th>
              <th>1st month</th>
              <th>2nd month</th>
              <th>3rd month</th>
              <th>Monthly Sales Target</th>
              <th>G1</th>
              <th>G2</th>
              <th>G2 - G1</th>
              <th>Sales Growth</th>
              <th>Product Growth</th>
              <th>% Result/ KPI (last month)</th>
              <th>% Result/ KPI (this month)</th>
              <?php foreach ($search->fetchSurveys(LeadTrackerSurvey::CUSTOMER_TYPE_NORMAL) as $survey) :?>
              <th colspan="<?=$survey->getTotalQuestion();?>"><?=$survey->content;?></th>
              <?php endforeach ;?>
              <?php foreach ($search->fetchSurveys(LeadTrackerSurvey::CUSTOMER_TYPE_POTENTIAL) as $survey) :?>
              <th colspan="<?=$survey->getTotalQuestion();?>"><?=$survey->content;?></th>
              <?php endforeach ;?>
              <?php foreach ($search->fetchSurveys(LeadTrackerSurvey::CUSTOMER_TYPE_LOYALTY) as $survey) :?>
              <th colspan="<?=$survey->getTotalQuestion();?>"><?=$survey->content;?></th>
              <?php endforeach ;?>
              <?php foreach ($search->fetchSurveys(LeadTrackerSurvey::CUSTOMER_TYPE_DANGEROUS) as $survey) :?>
              <th colspan="<?=$survey->getTotalQuestion();?>"><?=$survey->content;?></th>
              <?php endforeach ;?>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) : ?>
              <tr><td colspan="27"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $no => $model) : ?>
              <tr>
                <td class="center">
                  <a href='<?=Url::to(['customer-tracker/view', 'id' => $model->id]);?>'><?=FormatConverter::convertToDate(strtotime($model->converted_at), 'd-m-Y H:i');?></a></td>
                <td class="center">
                  <?=$model->getCustomerTrackerLabel();?>
                </td>
                <td class="center" <?php if ($model->is_loyalty):?> style="background-color: purple" <?php endif;?>>
                  <?=$model->getCustomerMonthlyLabel();?>
                </td>
                <td class="center" <?php if ($model->is_dangerous):?> style="background-color: red" <?php endif;?>><a href="<?=$model->link;?>" target="_blank"><?=$model->name;?></a></td>
                <td class="center"><?=$model->getCountryName();?></td>
                <td class="center"><?=$model->phone;?></td>
                <td class="center"><?=$model->email;?></td>
                <td class="center"><?=$model->getChannelLabels();?></td>
                <td class="center"><?=$model->game ? $model->game->title : '-';?></td>
                <td class="center"><?=$model->saler ? $model->saler->getName() : '-';?></td>

                <td class="center"><?=$model->first_order_at;?></td>
                <td class="center"><?=$model->sale_month_1;?></td>
                <td class="center"><?=$model->sale_month_2;?></td>
                <td class="center"><?=$model->sale_month_3;?></td>
                <td class="center"><?=$model->getCurrentSaleTarget();?></td>
                <td class="center"><?=$model->growth_rate_1;?></td>
                <td class="center"><?=$model->growth_rate_2;?></td>
                <td class="center"><?=$model->growth_speed;?></td>
                <td class="center">
                  <?php if ($model->sale_growth) :?>
                  <span class="label label-success">YES</span>
                  <?php else : ?>
                  <span class="label label-default">NO</span>
                  <?php endif;?>
                </td>
                <td class="center"><?=$model->number_of_game;?></td>
                <td class="center"><?=$model->kpi_growth * 100;?>%</td>
                <td class="center">
                  <?php
                  $currentSaleTarget = $model->getCurrentSaleTarget();
                  if ($currentSaleTarget) {
                    $currentSale = ArrayHelper::getValue($saleForUser, $model->user_id, 0);
                    echo sprintf("%s%s", round(( $currentSale / $currentSaleTarget) * 100, 2), "%");
                  } else {
                    echo '0%';
                  }
                  ?>
                </td>
                <td class="center">
                  <?php if ($model->is_potential_customer) :?>
                  <span class="label label-success">YES</span>
                  <?php else : ?>
                  <span class="label label-default">NO</span>
                  <?php endif;?>
                </td>
                <td class="center"><?=$model->potential_customer_at;?></td>
                <td class="center">
                  <?php if ($model->is_key_customer) :?>
                  <span class="label label-success">YES</span>
                  <?php else : ?>
                  <span class="label label-default">NO</span>
                  <?php endif;?>
                </td>
                <td class="center"><?=$model->key_customer_at;?></td>
                <td class="center">
                  <a href="<?=Url::to(['customer-tracker/edit', 'id' => $model->id]);?>" class="btn btn-sm green btn-outline filter-submit margin-bottom">Edit</a>
                  <a href="<?=Url::to(['customer-tracker/calculate', 'id' => $model->id]);?>" class="btn btn-sm blue btn-outline filter-submit margin-bottom ajax-link">Calculate</a>
                  <a href="<?=Url::to(['customer-tracker/delete', 'id' => $model->id]);?>" class="btn btn-sm red btn-outline delete margin-bottom">Delete</a>
                </td>
                <?php 
                $surveyAnswersByTracker = ArrayHelper::getValue($surveyAnswers, $model->id, []);
                $surveyAnswersByQuestion = ArrayHelper::map($surveyAnswersByTracker, 'question_id', 'value');
                ?>
                <?php foreach ($search->fetchSurveys(LeadTrackerSurvey::CUSTOMER_TYPE_NORMAL) as $survey) :?>
                  <?php $isShowQuestion = count($survey->questions) >= 2;?>
                  <?php foreach ($survey->questions as $question) :?>
                  <td>
                    <?=$isShowQuestion ? $question->question . ": " : '';?>
                    <?=ArrayHelper::getValue($surveyAnswersByQuestion, $question->id, '-');?>
                  </td>
                  <?php endforeach;?>
                <?php endforeach ;?>
                <?php foreach ($search->fetchSurveys(LeadTrackerSurvey::CUSTOMER_TYPE_POTENTIAL) as $survey) :?>
                  <?php foreach ($survey->questions as $question) :?>
                  <td>
                    <?=$isShowQuestion ? $question->question . ": " : '';?>
                    <?=ArrayHelper::getValue($surveyAnswersByQuestion, $question->id, '-');?>
                  </td>
                  <?php endforeach;?>
                <?php endforeach ;?>
                <?php foreach ($search->fetchSurveys(LeadTrackerSurvey::CUSTOMER_TYPE_LOYALTY) as $survey) :?>
                  <?php foreach ($survey->questions as $question) :?>
                  <td>
                    <?=$isShowQuestion ? $question->question . ": " : '';?>
                    <?=ArrayHelper::getValue($surveyAnswersByQuestion, $question->id, '-');?>
                  </td>
                  <?php endforeach;?>
                <?php endforeach ;?>
                <?php foreach ($search->fetchSurveys(LeadTrackerSurvey::CUSTOMER_TYPE_DANGEROUS) as $survey) :?>
                  <?php foreach ($survey->questions as $question) :?>
                  <td>
                    <?=$isShowQuestion ? $question->question . ": " : '';?>
                    <?=ArrayHelper::getValue($surveyAnswersByQuestion, $question->id, '-');?>
                  </td>
                  <?php endforeach;?>
                <?php endforeach ;?>
              </tr>
              <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
<?php
$script = <<< JS
$('#myTable').DataTable({
  order: [],
});

$(".ajax-link").ajax_action({
  method: 'GET',
  callback: function(eletement, data) {
    location.reload();
  },
  error: function(element, errors) {
    console.log(errors);
    alert(errors);
  }
});

$(".delete").ajax_action({
  confirm: true,
  confirm_text: 'Bạn có muốn xoá customer tracker này không?',
  callback: function(eletement, data) {
    location.reload();
  }
});

$('[data-toggle="popover"]').popover({
  container: 'body',
  trigger: 'hover'
});
JS;
$this->registerJs($script);
?>