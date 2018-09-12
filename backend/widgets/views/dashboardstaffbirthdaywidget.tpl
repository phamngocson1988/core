<div class="col-lg-6 col-xs-12 col-sm-12">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-share font-dark hide"></i>
                <span class="caption-subject font-dark bold uppercase">{Yii::t('app', 'incomming_staff_birthday')}</span>
            </div>
        </div>
        <div class="portlet-body">
            <div class="scroller" style="height: auto; max-height: 300px" data-always-visible="1" data-rail-visible="0">
                {if !$models}
                    <div style="text-align: center;">{Yii::t('app', 'no_data_found')}</div>
                {else}
                <ul class="feeds">
                	{foreach $models as $model}
                    <li>
                        <div class="col1">
                            <div class="cont">
                                <div class="cont-col1">
                                    <div class="label label-sm label-info">
                                        <i class="fa fa-birthday-cake"></i>
                                    </div>
                                </div>
                                <div class="cont-col2">
                                    <div class="desc">{$model->name}
                                        <span class="label label-sm label-warning "> {$model->birthday} </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col2">
                            <div class="date"> {Yii::t('app', '{n}_day_left', ['n' => $model->getBirthdayLeft()])}</div>
                        </div>
                    </li>
                   {/foreach}
                </ul>
                {/if}
            </div>
        </div>
    </div>
</div>
