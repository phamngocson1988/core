{use class='backend\widgets\DashboardTaskWidget'}
{use class='backend\widgets\DashboardStatisticsWidget'}
{use class='backend\widgets\DashboardStaffBirthdayWidget'}
{use class='backend\widgets\DashboardTaskStatsWidget'}

<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <span>{Yii::t('app', 'home')}</span>
    </li>
  </ul>
  <div class="page-toolbar">
    <div class="pull-right btn btn-sm">
      <i class="icon-calendar"></i>&nbsp;
      <span class="thin uppercase hidden-xs">{date('l jS \of F Y')}</span>&nbsp;
    </div>
  </div>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> {Yii::t('app', 'admin_dashboard')}
  <small>{Yii::t('app', 'admin_dashboard_intro')}</small>
</h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN DASHBOARD STATS 1-->
{DashboardStatisticsWidget::widget()}
<div class="clearfix"></div>
<!-- END DASHBOARD STATS 1-->

<div class="row">
{DashboardTaskWidget::widget()}
{DashboardStaffBirthdayWidget::widget()}
</div>

<div class="row">
  {DashboardTaskStatsWidget::widget()}
</div>

