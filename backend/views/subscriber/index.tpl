{use class='yii\widgets\LinkPager'}
{use class='yii\widgets\Pjax' type='block'}
<div class="page-content-wrapper">
  <!-- BEGIN CONTENT BODY -->
  <div class="page-content">
    <!-- BEGIN PAGE BAR -->
    <div class="page-bar">
      <ul class="page-breadcrumb">
        <li>
          <a href="/">Home</a>
          <i class="fa fa-circle"></i>
        </li>
        <li>
          <span>Manage Subscribers</span>
        </li>
      </ul>
    </div>
    <!-- END PAGE BAR -->
    <!-- BEGIN PAGE TITLE-->
    <h1 class="page-title">Manage Subscribers</h1>
    <!-- END PAGE TITLE-->
    <div class="row">
      <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
          <div class="portlet-title">
            <div class="caption font-dark">
              <i class="icon-settings font-dark"></i>
              <span class="caption-subject bold uppercase"> Manage Subscribers</span>
            </div>
          </div>
          <div class="portlet-body">
            <div class="row margin-bottom-10">
              <form method="GET">
                <div class="form-group col-md-4">
                  <label>Keyword: </label> <input type="search" class="form-control"
                    placeholder="Keyword" name="q" value="{$form->q}">
                </div>
                <div class="form-group col-md-3">
                  <button type="submit" class="btn btn-success table-group-action-submit"
                    style="margin-top:
                    25px;">
                  <i class="fa fa-check"></i> Search
                  </button>
                </div>
              </form>
            </div>
            {Pjax}
            <table class="table table-striped table-bordered table-hover table-checkable">
              <thead>
                <tr>
                  <th> No </th>
                  <th> Email </th>
                  <th> Created Date </th>
                </tr>
              </thead>
              <tbody>
                {if $models}
                {foreach $models as $key => $model}
                <tr>
                  <td>{$key + $pages->offset + 1}</td>
                  <td>{$model->email}</td>
                  <td>{$model->getCreatedAt(true)}</td>
                {/foreach}
                {else}
                <tr>
                  <td colspan="3">No data</td>
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
  </div>
</div>
<!-- END CONTENT BODY -->