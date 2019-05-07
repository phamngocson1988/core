{use class='yii\widgets\ActiveForm' type='block'}

<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/">{Yii::t('app', 'home')}</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Quản lý danh bạ</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Quản lý danh bạ</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Quản lý danh bạ</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            {ActiveForm assign='form' options=['class' => 'form-horizontal form-row-seperated']}
            <input type="submit" class="btn green" value="Run import"/>
            {/ActiveForm}
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
        </div>
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th style="width: 5%;"> {Yii::t('app', 'no')} </th>
              <th style="width: 15%;"> Tên </th>
              <th style="width: 25%;"> Số điện thoại </th>
              <th style="width: 10%;"> Mô tả </th>
            </tr>
          </thead>
          <tbody>
            {if $models}
            {foreach $models as $key => $model}
            <tr>
              <td>{$key + 1}</td>
              <td>{$model->name}</td>
              <td>{$model->phone}</td>
              <td>{$model->description}</td>
            </tr>
            {/foreach}
            {else}
            <tr>
              <td colspan="4">{Yii::t('app', 'no_data_found')}</td>
            </tr>
            {/if}
          </tbody>
        </table>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>