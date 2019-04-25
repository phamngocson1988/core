{use class='yii\widgets\LinkPager'}
{use class='yii\widgets\Pjax' type='block'}
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/">{Yii::t('app', 'home')}</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>{Yii::t('app', 'manage_customers')}</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">{Yii::t('app', 'manage_customers')}</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> {Yii::t('app', 'manage_customers')}</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="{url route='customer/create' ref=$ref}">{Yii::t('app', 'add_new')}</a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
          <form method="GET">
            <div class="form-group col-md-4">
              <label>{Yii::t('app', 'keyword')}: </label> <input type="search" class="form-control"
                placeholder="Keyword" name="q" value="{$form->q}">
            </div>
            <div class="form-group col-md-3">
              <button type="submit" class="btn btn-success table-group-action-submit"
                style="margin-top:
                25px;">
              <i class="fa fa-check"></i> {Yii::t('app', 'search')}
              </button>
            </div>
          </form>
        </div>
        {Pjax}
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th style="width: 5%;"> {Yii::t('app', 'no')} </th>
              <th style="width: 25%;"> Công ty </th>
              <th style="width: 15%;"> Người đại diện </th>
              <th style="width: 10%;"> Số dư </th>
              <th style="width: 15%;"> Số điện thoại </th>
              <th style="width: 10%;"> Số profile </th>
              <th style="width: 10%;"> {Yii::t('app', 'status')} </th>
              <th style="width: 10%;" class="dt-center"> {Yii::t('app', 'actions')} </th>
            </tr>
          </thead>
          <tbody>
            {if $models}
            {foreach $models as $key => $model}
            <tr>
              <td>{$key + $pages->offset + 1}</td>
              <td>{$model->company}</td>
              <td>{$model->name}</td>
              <td>{$model->balance} VNĐ</td>
              <td>{$model->phone}</td>
              <td>
                <a class="btn purple btn-outline sbold" data-toggle="modal" href="#profiles{$model->id}">{$model->countProfiles()}</a>
                <div class="modal fade bs-modal-lg" id="profiles{$model->id}" tabindex="-1" role="dialog" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Profiles - {$model->company}</h4>
                      </div>
                      <div class="modal-body">
                      <table class="table table-striped table-bordered table-hover table-checkable">
                        <thead>
                          <tr>
                            <th style="width: 10%;"> Số đầu </th>
                            <th style="width: 10%;"> Cổng </th>
                            <th style="width: 10%;"> Loại </th>
                            <th style="width: 20%;"> Giá tiền </th>
                            <th style="width: 40%;"> API </th>
                            <th style="width: 10%;" class="dt-center"> {Yii::t('app', 'actions')} </th>
                          </tr>
                        </thead>
                        <tbody>
                          {foreach $model->profiles as $profile}
                          <tr>
                            <td>{$profile->prefix}</td>
                            <td>{$profile->port}</td>
                            <td>{$profile->action}</td>
                            <td>{$profile->price}</td>
                            <td>{$profile->api}</td>
                            <td>
                              <a class="btn btn-xs grey-salsa" href="{url route='customer/edit-profile' id=$profile->id}"><i class="fa fa-edit"></i></a>
                            </td>
                          </tr>
                          {/foreach}
                        </tbody>
                      </table>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
              </td>
              <td>{$model->getStatusLabel()}</td>
              <td>
                <a class="btn btn-xs grey-salsa" href="{url route='customer/edit' id=$model->id}"><i class="fa fa-edit"></i></a>
                <a class="btn btn-xs grey-salsa" href="{url route='customer/create-profile' id=$model->id}"><i class="fa fa-plus"></i></a>
                <a class="btn btn-xs grey-salsa" href="{url route='customer/topup' id=$model->id}"><i class="fa fa-arrow-up"></i></a>
                <a class="btn btn-xs grey-salsa" href="{url route='customer/history' id=$model->id}"><i class="fa fa-list"></i></a>
              </td>
            </tr>
            {/foreach}
            {else}
            <tr>
              <td colspan="8">{Yii::t('app', 'no_data_found')}</td>
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
$(".delete-customer").ajax_action({
  confirm: true,
  confirm_text: '{/literal}{Yii::t('app', 'confirm_delete_customer')}{literal}',
  callback: function(eletement, data) {
    location.reload();
  }
});
$(".active-customer").ajax_action({
  confirm: true,
  confirm_text: '{/literal}{Yii::t('app', 'confirm_enable_customer')}{literal}',
  callback: function(eletement, data) {
    location.reload();
  }
});
{/literal}
{/registerJs}