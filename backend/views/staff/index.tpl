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
      <span>{Yii::t('app', 'manage_staffs')}</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">{Yii::t('app', 'manage_staffs')}</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> {Yii::t('app', 'manage_staffs')}</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="{url route='staff/create' ref=$ref}">{Yii::t('app', 'add_new')}</a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
          <form method="GET">
            <div class="form-group col-md-2">
              <label>{Yii::t('app', 'gender')}: </label>
              <select class="form-control" name="gender">
                <option value="">{Yii::t('app', 'all')}</option>
                {foreach $form->getStaffGenders() as $statusKey => $statusLabel}
                <option value="{$statusKey}" {if (string)$statusKey === $form->gender} selected {/if}>{$statusLabel}</option>
                {/foreach}
              </select>
            </div>
            <div class="form-group col-md-2">
              <label>{Yii::t('app', 'branch')}: </label>
              <select class="form-control" name="branch">
                <option value="">{Yii::t('app', 'all')}</option>
                {foreach $form->getBranches() as $branchKey => $branchLabel}
                <option value="{$branchKey}" {if (string)$branchKey === $form->branch} selected {/if}>{$branchLabel}</option>
                {/foreach}
              </select>
            </div>
            <div class="form-group col-md-3">
              <label>{Yii::t('app', 'department')}: </label>
              <select class="form-control" name="department">
                <option value="">{Yii::t('app', 'all')}</option>
                {foreach $form->getDepartments() as $departmentKey => $departmentLabel}
                <option value="{$departmentKey}" {if (string)$departmentKey === $form->department} selected {/if}>{$departmentLabel}</option>
                {/foreach}
              </select>
            </div>
            <div class="form-group col-md-3">
              <label>{Yii::t('app', 'keyword')}: </label> <input type="search" class="form-control"
                placeholder="Keyword" name="q" value="{$form->q}">
            </div>
            <div class="form-group col-md-2">
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
              <th style="width: 20%;"> {Yii::t('app', 'name')} </th>
              <th style="width: 20%;"> {Yii::t('app', 'email')} </th>
              <th style="width: 20%;"> {Yii::t('app', 'contact_phone')} </th>
              <th style="width: 10%;"> {Yii::t('app', 'branch')} </th>
              <th style="width: 20%;"> {Yii::t('app', 'department')} </th>
              <th style="width: 5%;" class="dt-center"> {Yii::t('app', 'actions')} </th>
            </tr>
          </thead>
          <tbody>
            {if $models}
            {foreach $models as $key => $model}
            <tr>
              <td>{$key + $pages->offset + 1}</td>
              <td>{$model->name}</td>
              <td>{$model->email}</td>
              <td>{$model->phone}</td>
              <td>{$model->getBranchName()}</td>
              <td>{$model->getDepartmentName()}</td>
              <td>
                <a href='{url route="staff/edit" id=$model->id ref=$ref}' class="btn btn-xs grey-salsa popovers" data-container="body" data-trigger="hover" data-content="{Yii::t('app', 'edit_staff')}"><i class="fa fa-pencil"></i></a>
              </td>
            </tr>
            {/foreach}
            {else}
            <tr>
              <td colspan="7">{Yii::t('app', 'no_data_found')}</td>
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