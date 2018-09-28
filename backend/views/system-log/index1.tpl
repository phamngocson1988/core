{use class='backend\components\gridview\GridView'}
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/">{Yii::t('app', 'home')}</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>{Yii::t('app', 'manage_system_logs')}</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">{Yii::t('app', 'manage_system_logs')}</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> {Yii::t('app', 'manage_system_logs')}</span>
        </div>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
          <form method="GET">
            <div class="form-group col-md-4">
              <label>{Yii::t('app', 'keyword')}: </label> <input type="search" class="form-control"
                placeholder="{Yii::t('app', 'keyword')}" name="description" value="{$form->description}">
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
        {GridView::widget([
          'dataProvider' => $form->getDataProvider(),
          'columns' => [
            [
              'class' => 'yii\grid\SerialColumn',
              'contentOptions' => ['style' => 'vertical-align: middle;'],
              'header' => Yii::t('app', 'no'),
              'headerOptions' => ['style' => 'width: 5%;']
            ],
            [
              'attribute' => 'created_at',
              'contentOptions' => ['style' => 'vertical-align: middle;'],
              'header' => Yii::t('app', 'created_time'),
              'headerOptions' => ['style' => 'width: 10%;']
            ],
            [
              'header' => Yii::t('app', 'creator'),
              'attribute' => 'id',
              'filter' => true,
              'contentOptions' => ['style' => 'vertical-align: middle;'],
              'headerOptions' => ['style' => 'width: 25%;']
            ],
            [
              'header' => Yii::t('app', 'action'),
              'attribute' => 'action',
              'filter' => true,
              'contentOptions' => ['style' => 'vertical-align: middle;'],
              'headerOptions' => ['style' => 'width: 15%;']
            ],
            [
              'header' => Yii::t('app', 'description'),
              'attribute' => 'description',
              'filter' => true,
              'contentOptions' => ['style' => 'vertical-align: middle;'],
              'headerOptions' => ['style' => 'width: 15%;']
            ],
            [
              'class' => 'yii\grid\ActionColumn',
              'header' => Yii::t('app', 'action'),
              'headerOptions' => ['style' => 'width: 10%;']
            ]            
          ]
        ])}
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
{registerJs}
{literal}

{/literal}
{/registerJs}