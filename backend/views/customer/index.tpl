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
                <a class="btn purple btn-outline sbold" data-toggle="modal" href="#profiles{$model->id}">{$model->countDialers()}</a>
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
                            <th style="width: 10%;"> Số </th>
                            <th style="width: 10%;"> Phần mở rộng </th>
                            <th style="width: 10%;"> Domain </th>
                            <th style="width: 20%;"> Loại </th>
                            <th style="width: 40%;"> Chi phí </th>
                            <th style="width: 10%;" class="dt-center"> {Yii::t('app', 'actions')} </th>
                          </tr>
                        </thead>
                        <tbody>
                          {foreach $model->dialers as $customerDialer}
                          <tr>
                            <td>{$customerDialer->dialer->number}</td>
                            <td>{$customerDialer->dialer->extend}</td>
                            <td>{$customerDialer->dialer->domain}</td>
                            <td>{$customerDialer->dialer->action}</td>
                            <td>
                            {if ($customerDialer->dialer->action == 'call')}
                            Call: {$customerDialer->call}
                            {else}
                            viettel: {$customerDialer->viettel},
                            mobifone: {$customerDialer->mobifone},
                            vinaphone: {$customerDialer->vinaphone},
                            vinamobile: {$customerDialer->vinamobile},
                            gmobile: {$customerDialer->gmobile},
                            other: {$customerDialer->other}
                            {/if}
                            </td>
                            <td>
                              <a class="btn btn-xs grey-salsa" href="{url route='customer/edit-dialer' id=$customerDialer->id}"><i class="fa fa-edit"></i></a>
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
                <a class="btn btn-xs grey-salsa tooltips" href="{url route='customer/edit' id=$model->id}" data-container="body" data-original-title="Chỉnh sửa"><i class="fa fa-edit"></i></a>
                <!--<a class="btn btn-xs grey-salsa" href="{url route='customer/create-profile' id=$model->id}"><i class="fa fa-plus"></i></a>-->
                <a class="btn btn-xs grey-salsa tooltips" data-toggle="modal" href="#dialer{$model->id}" data-container="body" data-original-title="Thêm bộ số"><i class="fa fa-plus"></i></a>
                <a class="btn btn-xs grey-salsa tooltips" href="{url route='customer/topup' id=$model->id}" data-container="body" data-original-title="Nạp tiền"><i class="fa fa-arrow-up"></i></a>
                <a class="btn btn-xs grey-salsa tooltips" href="{url route='customer/history' id=$model->id}" data-container="body" data-original-title="Lịch sử giao dịch"><i class="fa fa-list"></i></a>

                <div class="modal fade bs-modal-lg" id="dialer{$model->id}" tabindex="-1" role="dialog" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Profiles - {$model->company}</h4>
                      </div>
                      <div class="modal-body">
                        <div class="row margin-bottom-10">
                          <form method="GET" action="{url route='customer/create-dialer' id=$model->id}">
                            <input type="hidden" name="id" value="{$model->id}"/>
                            <div class="form-group col-md-6">
                              <label>Loại bộ số: </label> 
                              <select class="form-control" name="type" aria-required="true" aria-invalid="false">
                                <option value="sms">SMS</option>
                                <option value="call">Call</option>
                              </select>
                            </div>
                            <div class="form-group col-md-6">
                              <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;"><i class="fa fa-forward"></i> Liên kết bộ số</button>
                            </div>
                          </form>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
                <a class="btn btn-xs grey-salsa delete tooltips" href="{url route='customer/delete' id=$model->id}" data-container="body" data-original-title="Xóa"><i class="fa fa-trash"></i></a>

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
$(".delete").ajax_action({
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