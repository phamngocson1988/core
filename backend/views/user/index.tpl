{use class='yii\widgets\LinkPager'}
{use class='yii\widgets\Pjax' type='block'}
{use class='backend\components\datepicker\DatePicker'}
{use class='common\models\User'}
{use class='common\models\Country'}
{use class='yii\widgets\ActiveForm' type='block'}
{use class='yii\helpers\Url'}
{use class='yii\helpers\ArrayHelper'}
{use  class='yii\web\JsExpression'}

<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/">{Yii::t('app', 'home')}</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Quản lý khách hàng</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Quản lý khách hàng</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Quản lý khách hàng</span>
        </div>
        <div class="actions">
          {if ($app->user->can('admin'))}
          <a role="button" class="btn btn-warning" href="{Url::current(['mode'=>'export'])}"><i class="fa fa-file-excel-o"></i> Export</a>
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="{url route='user/create' ref=$ref}">{Yii::t('app', 'add_new')}</a>
          </div>
          {/if}
        </div>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
          {ActiveForm assign='form' id='filter-form' method='get'}
            {$form->field($search, 'user_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            ])->widget(kartik\select2\Select2::classname(), [
              'initValueText' => ($search->user_id) ? sprintf("%s - %s", $search->getCustomer()->username, $search->getCustomer()->email) : '',
              'options' => ['class' => 'form-control', 'name' => 'user_id'],
              'pluginOptions' => [
                'placeholder' => 'Chọn khách hàng',
                'allowClear' => true,
                'minimumInputLength' => 3,
                'ajax' => [
                    'url' => Url::to(['user/suggestion']),
                    'dataType' => 'json',
                    'processResults' => new JsExpression('function (data) {return {results: data.data.items};}')
                ]
              ]
            ])->label('Khách hàng')}

            {$form->field($search, 'phone', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'phone']
            ])->textInput()->label('Số điện thoại')}

            {$form->field($search, 'country_code', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'country_code']
            ])->dropDownList(ArrayHelper::map(Country::fetchAll(), 'country_code', 'country_name'), ['prompt' => 'Quốc gia'])->label('Tên quốc gia')}

            {$form->field($search, 'game_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'game_id']
            ])->dropDownList($search->fetchGames(), ['prompt' => 'Tìm theo game'])->label('Tên game')}

            {$form->field($search, 'created_start', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'created_start']
            ])->widget(DatePicker::className(), [
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ])->label('Ngày tham gia từ')}
            {$form->field($search, 'created_end', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'created_end']
            ])->widget(DatePicker::className(), [
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ])->label('Ngày tham gia đến')}

            {$form->field($search, 'birthday_start', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'birthday_start']
            ])->widget(DatePicker::className(), [
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ])->label('Có sinh nhật từ')}
            {$form->field($search, 'birthday_end', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'birthday_end']
            ])->widget(DatePicker::className(), [
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ])->label('Có sinh nhật đến')}

            {$form->field($search, 'purchase_start', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'purchase_start']
            ])->widget(DatePicker::className(), [
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ])->label('Có đơn hàng từ')}
            {$form->field($search, 'purchase_end', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'purchase_end']
            ])->widget(DatePicker::className(), [
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ])->label('Có đơn hàng đến')}

            {$form->field($search, 'total_purchase_start', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'total_purchase_start']
            ])->textInput()->label('Tổng giá trị đơn hàng từ')}

            {$form->field($search, 'total_purchase_end', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'total_purchase_end']
            ])->textInput()->label('Tổng giá trị đơn hàng đến')}

            {$form->field($search, 'saler_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'saler_id']
            ])->dropDownList($search->fetchSalers(),  ['prompt' => 'Tìm nhân viên bán hàng'])->label('Nhân viên bán hàng')}

            {* $form->field($search, 'last_purchase_start', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'last_purchase_start']
            ])->widget(DatePicker::className(), [
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ])->label('Mua hàng lần cuối từ')}
            {$form->field($search, 'last_purchase_end', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'last_purchase_end']
            ])->widget(DatePicker::className(), [
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ])->label('Mua hàng lần cuối đến') *}

            {$form->field($search, 'is_reseller', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'is_reseller']
            ])->dropDownList(User::getResellerStatus(),  ['prompt' => 'Tất cả'])->label('Reseller/Khách hàng')}


            <div class="form-group col-md-4 col-lg-3">
              <button type="submit" class="btn btn-success table-group-action-submit"
                style="margin-top:
                25px;">
              <i class="fa fa-check"></i> {Yii::t('app', 'search')}
              </button>
            </div>
          {/ActiveForm}
        </div>
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th> ID </th>
              <th> Khách hàng </th>
              <th> Ngày sinh </th>
              <th> Email </th>
              <th> Username </th>
              <th> Số điện thoại </th>
              <th> Ngày đăng ký </th>
              <th> Quốc tịch </th>
              <th> Đơn hàng cuối cùng </th>
              <th> Tổng tiền nạp </th>
              <th> Tổng tiền mua hàng </th>
              <th> Số dư hiện tại</th>
              <th> Reseller/Khách hàng </th>
              <th> Đại lý/người bán </th>
              <th> Tác vụ </th>
            </tr>
          </thead>
          <tbody>
            {if $models}
            {foreach $models as $key => $model}
            <tr>
              <td>{$model->id}</td>
              <td>{$model->name}</td>
              <td>{$model->birthday}</td>
              <td>{$model->email}</td>
              <td>{$model->username}</td>
              <td>{$model->phone}</td>
              <td>{$model->created_at}</td>
              <td>{$model->getCountryName()}</td>
              <td>{$model->last_order_date}</td>
              <td>{$model->getWalletTopupAmount()}</td>
              <td>{$model->getWalletWithdrawAmount()}</td>
              <td>{$model->getWalletAmount()}</td>
              <td>
                {if ($app->user->can('sale_manager'))}
                {if $model->isReseller()}
                <a href="{url route='user/downgrade-reseller' id=$model->id}" class="btn btn-sm purple link-action tooltips" data-container="body" data-original-title="Bỏ tư cách nhà bán lẻ"><i class="fa fa-times"></i> Reseller </a>
                {else}
                <a href="{url route='user/upgrade-reseller' id=$model->id}" class="btn btn-sm default link-action tooltips" data-container="body" data-original-title="Nâng cấp lên nhà bán lẻ"><i class="fa fa-arrow-up"></i> Khách hàng </a>
                {/if}
                {/if}
              </td>
              <td></td>
              <td>
                {* <a class="btn btn-xs grey-salsa tooltips" href="{url route='user/edit' id=$model->id}" data-container="body" data-original-title="{Yii::t('app', 'edit_user')}"><i class="fa fa-pencil"></i></a>
                {if $app->user->id != $model->id}
                {if $model->isActive()}
                <a class="btn btn-xs grey-salsa delete-user tooltips" href="{url route='user/change-status' id=$model->id status='delete'}" data-container="body" data-original-title="{Yii::t('app', 'disable_user')}"><i class="fa fa-minus-circle"></i></a>
                {else}
                <a class="btn btn-xs grey-salsa active-user tooltips" href="{url route='user/change-status' id=$model->id status='active'}" data-container="body" data-original-title="{Yii::t('app', 'enable_user')}"><i class="fa fa-check-square"></i></a>
                {/if}
                {/if} *}
                {if ($app->user->can('sale_manager'))}
                {if $model->isActive()}
                <a class="btn btn-xs red tooltips link-action" href="{url route='user/inactive' id=$model->id}" data-container="body" data-original-title="Inactive this user"><i class="fa fa-times"></i></a>
                {/if}
                {if $model->isInactive()}
                <a class="btn btn-xs green tooltips link-action" href="{url route='user/active' id=$model->id}" data-container="body" data-original-title="Active this user"><i class="fa fa-check"></i></a>
                {/if}
                {/if}
                {if $app->user->can('admin')}
                <a class="btn btn-xs default tooltips" href="{url route='user/edit' id=$model->id}" data-container="body" data-original-title="Edit user"><i class="fa fa-pencil"></i></a>
                {/if}
              </td>
            </tr>
            {/foreach}
            {else}
            <tr>
              <td colspan="15">{Yii::t('app', 'no_data_found')}</td>
            </tr>
            {/if}
          </tbody>
        </table>
        {LinkPager::widget(['pagination' => $pages])}
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
{registerJs}
{literal}
$(".delete-user").ajax_action({
  confirm: true,
  confirm_text: '{/literal}{Yii::t('app', 'confirm_disable_user')}{literal}',
  callback: function(eletement, data) {
    location.reload();
  }
});
$(".active-user").ajax_action({
  confirm: true,
  confirm_text: '{/literal}{Yii::t('app', 'confirm_enable_user')}{literal}',
  callback: function(eletement, data) {
    location.reload();
  }
});
$(".link-action").ajax_action({
  confirm: true,
  confirm_text: 'Bạn có chắc muốn thực hiện tác vụ này?',
  callback: function(eletement, data) {
    location.reload();
  }
});
{/literal}
{/registerJs}