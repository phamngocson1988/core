{use class='yii\widgets\LinkPager'}
{use class='yii\widgets\ActiveForm' type='block'}
{use class='yii\widgets\Pjax' type='block'}
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/">{Yii::t('app', 'home')}</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Giá nhà cung cấp</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Giá nhà cung cấp</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Giá nhà cung cấp</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="{url route='game/create' ref=$ref}">{Yii::t('app', 'add_new')}</a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
          <form method="GET">
            <div class="form-group col-md-4">
              <label>{Yii::t('app', 'keyword')}: </label> <input type="search" class="form-control" placeholder="{Yii::t('app', 'keyword')}" name="q" value="{$q}">
            </div>
            <div class="form-group col-md-3">
              <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
                <i class="fa fa-check"></i> {Yii::t('app', 'search')}
              </button>
            </div>
          </form>
        </div>
        {Pjax}
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th> Mã game </th>
              <th> {Yii::t('app', 'image')} </th>
              <th> {Yii::t('app', 'title')} </th>
              <th> {Yii::t('app', 'status')} </th>
              <th class="dt-center"> {Yii::t('app', 'actions')} </th>
            </tr>
          </thead>
          <tbody>
              {if (!$models) }
              <tr><td colspan="5">{Yii::t('app', 'no_data_found')}</td></tr>
              {/if}
              {foreach $models as $key => $model}
              <tr>
                <td style="vertical-align: middle;">{$model->id}</td>
                <td style="vertical-align: middle;"><img src="{$model->getImageUrl('50x50')}" width="50px;" /></td>
                <td style="vertical-align: middle;">{$model->title}</td>
                <td style="vertical-align: middle;">
                  {if $model->status == 'Y'}
                  <span class="label label-success">{Yii::t('app', 'visible')}</span>
                  {elseif $model->status == 'N'}
                  <span class="label label-warning">{Yii::t('app', 'disable')}</span>
                  {elseif $model->status == 'D'}
                  <span class="label label-default">{Yii::t('app', 'deleted')}</span>
                  {/if}
                </td>
                <td style="vertical-align: middle;">
                  <a href='{url route="game/update-price" id=$model->id ref=$ref}' class="btn btn-xs grey-salsa tooltips" data-container="body" data-original-title="Cập nhật giá" data-pjax="0"><i class="fa fa-money"></i></a>
                  <a href='{url route="game/edit" id=$model->id ref=$ref}' class="btn btn-xs grey-salsa tooltips" data-container="body" data-original-title="Chỉnh sửa game" data-pjax="0"><i class="fa fa-pencil"></i></a>
                  {*<a class="btn btn-xs grey-salsa tooltips" data-container="body" data-original-title="{Yii::t('app', 'edit')}" data-pjax="0" data-toggle="modal" href="#prices-model{$model->id}"><i class="fa fa-pencil"></i></a>*}
                  <div class="modal fade" tabindex="-1" role="basic" aria-hidden="true" id="prices-model{$model->id}">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                          <h4 class="modal-title">Cập nhật giá cho game {$model->title}</h4>
                        </div>
                        {ActiveForm assign='nextForm' action={url route='game/update-price' id=$model->id} options=['class' => 'form-row-seperated update-price-form']}
                        <div class="modal-body"> 
                          {$nextForm->field($model, 'price1')->textInput()}
                          {$nextForm->field($model, 'price2')->textInput()}
                          {$nextForm->field($model, 'price3')->textInput()}
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn green">Xác nhận</button>
                        </div>
                        {/ActiveForm}
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
              {/foreach}
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
var newForm = new AjaxFormSubmit({element: '.update-price-form'});
newForm.success = function (data, form) {
  location.reload();
};
{/literal}
{/registerJs}