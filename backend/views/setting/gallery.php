<?php
use yii\widgets\ActiveForm;
use common\widgets\MultipleImageInputWidget;
$defaultImage = 'https://cdn.stylepark.com/manufacturers/h/hi-macs/produkte/solid-steel-grey/solid-steel-grey-1.jpg';
?>
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home');?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="javascript:;"><?=Yii::t('app', 'settings');?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span><?=Yii::t('app', 'application');?></span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Banner trang chủ</h1>
<!-- END PAGE TITLE-->
<?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-row-seperated']]);?>
<div class="row">
  <div class="col-md-12">
    <div class="portlet">
      <div class="portlet-title">
        <div class="caption">Hình ảnh</div>
        <div class="actions btn-set">
          <button type="submit" class="btn btn-success">
          <i class="fa fa-check"></i> <?=Yii::t('app', 'save');?>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
<?php for ($i = 1; $i <= 8; $i++) :?>
<?php $link = 'link' . $i;?>
<?php $type = 'type' . $i;?>
<div class="panel panel-default">
  <div class="panel-heading">Hình <?=$i;?></div>
  <div class="panel-body">
    <div class="row">
      <div class="col-sm-12 col-md-3">
        <div class="thumbnail">
            <?php switch ($model->$type) {
                case 'youtube':
                    echo '<iframe width="100%" height="200px" src="'.$model->$link.'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                    break;
                case 'mp4':
                    echo '<video width="100%" height="200px" controls><source src="'.$model->$link.'" type="video/mp4"></video>';
                    break;
                default:
                    echo '<img id="image'.$i.'" src="' . ($model->$link ? $model->$link : $defaultImage) . '" alt="100%x200" style="width: 100%; height: 200px; display: block;">';
                    break;
            }; 
            ?>
            <input type="file" id="file_upload<?=$i;?>" name="file_upload<?=$i;?>" style="display: none" />
        </div>
      </div>
      <div class="col-sm-12 col-md-9">
        <div class="row">
          <?=$form->field($model, 'title' . $i, [
            'labelOptions' => ['class' => 'col-md-4 control-label'],
            'template' => '{label}<div class="col-md-8">{input}{hint}{error}</div>'
          ])->textInput();?>
          <?=$form->field($model, 'content' . $i, [
            'labelOptions' => ['class' => 'col-md-4 control-label'],
            'template' => '{label}<div class="col-md-8">{input}{hint}{error}</div>'
          ])->textInput();?>
          <?=$form->field($model, 'href' . $i, [
            'labelOptions' => ['class' => 'col-md-4 control-label'],
            'template' => '{label}<div class="col-md-8">{input}{hint}{error}</div>'
          ])->textInput();?>

          <?=$form->field($model, $type, [
            'labelOptions' => ['class' => 'col-md-4 control-label'],
            'template' => '{label}<div class="col-md-8">{input}{hint}{error}</div>'
          ])->dropdownList($model->fetchTypeList())->label('Loại banner');?>
          <?=$form->field($model, $link, [
            'labelOptions' => ['class' => 'col-md-4 control-label'],
            'template' => '{label}<div class="col-md-8"><div class="input-group">{input}<span class="input-group-btn"><button class="btn btn-default" type="button" id="remove'.$i.'">Remove</button></span>{error}</div></div>',
            'inputOptions' => ['class' => 'form-control', 'id' => $link]
          ])->textInput()->label('Image should be 1460x708px');?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endfor;?>
<?php ActiveForm::end()?>
<?php
$script = <<< JS
var upload1 = new AjaxUploadFile({trigger_element: '#image1', file_element: '#file_upload1'});
upload1.callback = function(result) {
  $('#image1').attr('src', result[0].src);
  $('#link1').val(result[0].src)
}

var upload2 = new AjaxUploadFile({trigger_element: '#image2', file_element: '#file_upload2'});
upload2.callback = function(result) {
  $('#image2').attr('src', result[0].src);
  $('#link2').val(result[0].src)
}

var upload3 = new AjaxUploadFile({trigger_element: '#image3', file_element: '#file_upload3'});
upload3.callback = function(result) {
  $('#image3').attr('src', result[0].src);
  $('#link3').val(result[0].src)
}

var upload4 = new AjaxUploadFile({trigger_element: '#image4', file_element: '#file_upload4'});
upload4.callback = function(result) {
  $('#image4').attr('src', result[0].src);
  $('#link4').val(result[0].src)
}

var upload5 = new AjaxUploadFile({trigger_element: '#image5', file_element: '#file_upload5'});
upload5.callback = function(result) {
  $('#image5').attr('src', result[0].src);
  $('#link5').val(result[0].src)
}

var upload6 = new AjaxUploadFile({trigger_element: '#image6', file_element: '#file_upload6'});
upload6.callback = function(result) {
  $('#image6').attr('src', result[0].src);
  $('#link6').val(result[0].src)
}

var upload7 = new AjaxUploadFile({trigger_element: '#image7', file_element: '#file_upload7'});
upload7.callback = function(result) {
  $('#image7').attr('src', result[0].src);
  $('#link7').val(result[0].src)
}

var upload8 = new AjaxUploadFile({trigger_element: '#image8', file_element: '#file_upload8'});
upload8.callback = function(result) {
  $('#image8').attr('src', result[0].src);
  $('#link8').val(result[0].src)
}

$('#remove1').on('click', function(){
  $('#link1').val('');
});
$('#remove2').on('click', function(){
  $('#link2').val('');
});
$('#remove3').on('click', function(){
  $('#link3').val('');
});
$('#remove4').on('click', function(){
  $('#link4').val('');
});

$('#remove5').on('click', function(){
  $('#link5').val('');
});

$('#remove6').on('click', function(){
  $('#link6').val('');
});

$('#remove7').on('click', function(){
  $('#link7').val('');
});

$('#remove8').on('click', function(){
  $('#link8').val('');
});
JS;
$this->registerJs($script);
?>