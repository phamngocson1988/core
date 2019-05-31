{use class='yii\helpers\Html'}
{use class='yii\widgets\ActiveForm' type='block'}
{use class='yii\helpers\ArrayHelper'}
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/">{Yii::t('app', 'home')}</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="{url route='customer/index'}">Quản lý khách hàng</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Tạo khách hàng</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Tạo khách hàng</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    {ActiveForm assign='form' options=['class' => 'form-horizontal form-row-seperated'] id='signup-form'}
      <div class="portlet">
        <div class="portlet-title">
          <div class="caption">Tạo khách hàng</div>
          <div class="actions btn-set">
            <a href="{$back}" class="btn default">
            <i class="fa fa-angle-left"></i> {Yii::t('app', 'back')}</a>
            <button type="submit" class="btn btn-success">
            <i class="fa fa-check"></i> {Yii::t('app', 'save')}
            </button>
          </div>
        </div>
        <div class="portlet-body">
          <div class="tabbable-bordered">
            <ul class="nav nav-tabs">
              <li class="active">
                <a href="#tab_general" data-toggle="tab"> {Yii::t('app', 'main_content')} </a>
              </li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_general">
                <div class="form-body">
                  {$form->field($model, 'email', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()}
                  {$form->field($model, 'password', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->passwordInput()}
                  {$form->field($model, 'company', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()}
                  {$form->field($model, 'tax_code', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()}
                  {$form->field($model, 'name', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()}
                  {$form->field($model, 'phone', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()}
                  {$form->field($model, 'address', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()}
                  {$form->field($model, 'province_id', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>',
                    'inputOptions' => ['class' => 'form-control', 'id' => 'province']
                  ])->dropDownList(ArrayHelper::map($provinces, 'id', 'name'))}
                  {$form->field($model, 'city_id', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>',
                    'inputOptions' => ['class' => 'form-control', 'id' => 'city']
                  ])->dropDownList([])}
                  {$form->field($model, 'ward_id', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>',
                    'inputOptions' => ['class' => 'form-control', 'id' => 'ward']
                  ])->dropDownList([])}
                  {$form->field($model, 'status', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->dropDownList(['10' => 'Kích hoạt', '1' => 'Ngưng kích hoạt'])}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      {/ActiveForm}
  </div>
</div>
{registerJs}
{literal}
$("#province").on('change', function() {
  $.ajax({
    url: '{/literal}{url route='customer/cities'}{literal}' + '?id=' + $(this).val(),
    type: 'GET',
    dataType : 'json',
    success: function (result, textStatus, jqXHR) {
      if (result.status == false) {
          alert(result.errors.join("\n"));
          return false;
      } else {
          var cities = result.data.cities;
          var str = '';
          $.each(cities, function(index, value){
            str += "<option value='"+index+"'>"+value+"</option>";
          });
          $('#city').html(str);
          $("#city").trigger('change');
      }
    }
  });
});
$("#city").on('change', function() {
  $.ajax({
    url: '{/literal}{url route='customer/wards'}{literal}' + '?id=' + $(this).val(),
    type: 'GET',
    dataType : 'json',
    success: function (result, textStatus, jqXHR) {
      if (result.status == false) {
          alert(result.errors.join("\n"));
          return false;
      } else {
          var wards = result.data.wards;
          var str = '';
          $.each(wards, function(index, value){
            str += "<option value='"+index+"'>"+value+"</option>";
          });
          $('#ward').html(str);
      }
          
    }
  });
});
$("#province").trigger('change');
{/literal}
{/registerJs}