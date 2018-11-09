{use class='yii\widgets\LinkPager'}
{use class='backend\components\gridview\GridView'}
{use class='yii\widgets\Pjax' type='block'}
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/">{Yii::t('app', 'home')}</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>{Yii::t('app', 'manage_post_categories')}</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">{Yii::t('app', 'manage_post_categories')}</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> {Yii::t('app', 'manage_post_categories')}</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="{url route='category/create' ref=$ref}">{Yii::t('app', 'add_new')}</a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        {Pjax}
        {GridView::widget([
            'dataProvider' => $form->getProvider(),
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\CheckboxColumn'],
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'class' => 'backend\components\gridview\ImageColumn',
                    'label' => Yii::t('app', 'image'),
                    'format' => 'html',
                    'image_options' => ['width' => '50', 'height' => '50']
                ],
                [
                    'label' => Yii::t('app', 'name'),
                    'format' => 'text',
                    'attribute' => 'name',
                    'filter' => '<input>'
                ],
                [
                    'attribute' => 'visible',
                    'filter' => ['Y' => 'Yes', 'N' => 'No'],
                    'filterInputOptions' => ['prompt' => 'All educations', 'class' => 'form-control', 'id' => null]
                ],
                [
                  'class' => 'backend\components\gridview\ActionColumn',
                  'buttons' => [
                    'edit' => ['name' => 'Edit'],
                    'new' => ['name' => 'New']
                  ],
                  'visibleButtons' => [
                    'new' => false
                  ]
                ]
            ]
        ])}
        {/Pjax}
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
{registerJs}
{literal}
$(".delete-action").ajax_action({
  confirm: true,
  confirm_text: '{/literal}{Yii::t('app', 'confirm_delete_category')}{literal}',
  callback: function(eletement, data) {
    location.reload();
  }
});
{/literal}
{/registerJs}