{use class='yii\widgets\LinkPager'}
{use class='yii\widgets\Pjax' type='block'}
{use class='dosamigos\datepicker\DatePicker'}
{use class='common\models\User'}
{use class='yii\widgets\ActiveForm' type='block'}

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
         <!-- <a role="button" class="btn btn-warning" href="<?=Url::current(['mode'=>'export']);?>"><i class="fa fa-file-excel-o"></i> Export</a>
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="{url route='user/create' ref=$ref}">{Yii::t('app', 'add_new')}</a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
          {ActiveForm assign='form' id='filter-form' method='get'}
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

            {$form->field($search, 'country_code', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'country_code']
            ])->dropDownList(Yii::$app->params['country_code'], ['prompt' => 'Quốc gia'])->label('Tên quốc gia')}

            {$form->field($search, 'game_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'game_id']
            ])->dropDownList($search->fetchGames(), ['prompt' => 'Tìm theo game'])->label('Tên game')}

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
        {Pjax}
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th style="width: 2%;"> {Yii::t('app', 'no')} </th>
              <th style="width: 5%;"> Khách hàng </th>
              <th style="width: 5%;"> Ngày sinh </th>
              <th style="width: 10%;"> Email </th>
              <th style="width: 5%;"> Số điện thoại </th>
              <th style="width: 10%;"> Ngày đăng ký </th>
              <th style="width: 8%;"> Quốc tịch </th>
              <th style="width: 10%;"> Đơn hàng cuối cùng </th>
              <th style="width: 10%;"> Tổng tiền nạp </th>
              <th style="width: 10%;"> Tổng tiền mua hàng </th>
              <th style="width: 5%;"> Reseller/Khách hàng </th>
              <th style="width: 5%;"> Đại lý/người bán </th>
            </tr>
          </thead>
          <tbody>
            {if $models}
            {foreach $models as $key => $model}
            <tr>
              <td>{$key + $pages->offset + 1}</td>
              <td>{$model->name}</td>
              <td>{$model->birthday}</td>
              <td>{$model->email}</td>
              <td>{$model->country_code} {$model->phone}</td>
              <td>{$model->created_at}</td>
              <td>{$model->getCountryName()}</td>
              <td>{$model->last_order_date}</td>
              <td>{$model->getWalletTopupAmount()}</td>
              <td>{$model->getWalletWithdrawAmount()}</td>
              <td>{if $model->isReseller()}Reseller{else}Khách hàng{/if}</td>
              <td></td>
              <!-- <td>
                <a class="btn btn-xs grey-salsa tooltips" href="{url route='user/edit' id=$model->id}" data-container="body" data-original-title="{Yii::t('app', 'edit_user')}"><i class="fa fa-pencil"></i></a>
                {if $app->user->id != $model->id}
                {if $model->isActive()}
                <a class="btn btn-xs grey-salsa delete-user tooltips" href="{url route='user/change-status' id=$model->id status='delete'}" data-container="body" data-original-title="{Yii::t('app', 'disable_user')}"><i class="fa fa-minus-circle"></i></a>
                {else}
                <a class="btn btn-xs grey-salsa active-user tooltips" href="{url route='user/change-status' id=$model->id status='active'}" data-container="body" data-original-title="{Yii::t('app', 'enable_user')}"><i class="fa fa-check-square"></i></a>
                {/if}
                {/if}
              </td> -->
            </tr>
            {/foreach}
            {else}
            <tr>
              <td colspan="12">{Yii::t('app', 'no_data_found')}</td>
            </tr>
            {/if}
          </tbody>
        </table>
        {LinkPager::widget(['pagination' => $pages])}
        {/Pjax}
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
{/literal}
{/registerJs}