<div class="col-lg-6 col-xs-12 col-sm-12">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-cursor font-dark hide"></i>
                <span class="caption-subject font-dark bold uppercase">{Yii::t('app', 'task_statistics')}</span>
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="easy-pie-chart">
                        <div class="number transactions" data-percent="{$statistics['new']}">
                            <span>{$statistics['new']}</span>% </div>
                        <a class="title" href="{url route='task/index' assignee=$app->user->id status=['new']}"> {Yii::t('app', 'task_new')}
                            <i class="icon-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="margin-bottom-10 visible-sm"> </div>
                <div class="col-md-4">
                    <div class="easy-pie-chart">
                        <div class="number visits" data-percent="{$statistics['inprogress']}">
                            <span>{$statistics['inprogress']}</span>% </div>
                        <a class="title" href="{url route='task/index' assignee=$app->user->id status=['inprogress']}"> {Yii::t('app', 'task_inprogress')}
                            <i class="icon-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="margin-bottom-10 visible-sm"> </div>
                <div class="col-md-4">
                    <div class="easy-pie-chart">
                        <div class="number bounce" data-percent="{$statistics['done']}">
                            <span>{$statistics['done']}</span>% </div>
                        <a class="title" href="{url route='task/index' assignee=$app->user->id status=['done']}"> {Yii::t('app', 'task_done')}
                            <i class="icon-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>