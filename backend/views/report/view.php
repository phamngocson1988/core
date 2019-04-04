<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use dosamigos\datepicker\DateRangePicker;
use yii\web\JsExpression;
use common\models\Game;
use common\models\Product;
use common\models\File;
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['report/index'])?>">Báo cáo</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Xem đơn hàng</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Xem đơn hàng</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
      <div class="portlet">
        <div class="portlet-title">
          <div class="caption">
            <i class="icon-settings font-dark"></i>
            <span class="caption-subject font-dark sbold uppercase"> Order #<?=$order->id;?>
              <span class="hidden-xs">| <?=$order->created_at;?> </span>
            </span>
          </div>
          <div class="actions btn-set">
            <a href="<?=$back;?>" class="btn default">
            <i class="fa fa-angle-left"></i> <?=Yii::t('app', 'back')?></a>
          </div>
        </div>
        <div class="portlet-body">
          <div class="tabbable-bordered">
            <ul class="nav nav-tabs">
              <li class="active">
                <a href="#tab_general" data-toggle="tab"> <?=Yii::t('app', 'main_content')?></a>
              </li>
              <li>
                <a href="#images" data-toggle="tab"> Hình ảnh</a>
              </li>
              <li>
                <a href="#complain" data-toggle="tab"> Phản hồi</a>
              </li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_general">
                <div class="row">
                  <div class="col-md-12 col-sm-12">
                    <div class="portlet grey-cascade box">
                      <div class="portlet-title">
                        <div class="caption">
                          <i class="fa fa-cogs"></i>Game
                        </div>
                      </div>
                      <div class="portlet-body">
                        <div class="table-responsive">
                          <table class="table table-hover table-bordered table-striped">
                            <thead>
                              <tr>
                                <th> Tên game </th>
                                <th> Số đơn vị </th>
                                <th> Tên đơn vị </th>
                                <th> Số lượng </th>
                                <th> Tổng số </th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td><?=$item->item_title;?></td>
                                <td><?=$item->unit_name;?></td>
                                <td><?=$item->unit;?></td>
                                <td><?=$item->quantity;?></td>
                                <td><?=$item->total_unit;?></td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4 col-sm-12">
                    <div class="portlet blue-hoki box">
                      <div class="portlet-title">
                        <div class="caption">
                          <i class="fa fa-cogs"></i>Order Details 
                        </div>
                      </div>
                      <div class="portlet-body">
                        <div class="row static-info">
                          <div class="col-md-5 name"> Order #: </div>
                          <div class="col-md-7 value"> <?=$order->id;?></div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5 name"> Order Date & Time: </div>
                          <div class="col-md-7 value"> <?=$order->created_at;?> </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5 name"> Order Status: </div>
                          <div class="col-md-7 value">
                            <span class="label label-success"> <?=$order->status;?> </span>
                          </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5 name"> Total: </div>
                          <div class="col-md-7 value"> (K) <?=number_format($order->total_price);?> </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5 name"> Payment Information: </div>
                          <div class="col-md-7 value"> King Coin </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 col-sm-12">
                    <div class="portlet blue-hoki box">
                      <div class="portlet-title">
                        <div class="caption">
                          <i class="fa fa-cogs"></i>Customer Information 
                        </div>
                      </div>
                      <?php $customer = $order->customer;?>
                      <div class="portlet-body">
                        <div class="row static-info">
                          <div class="col-md-5 name"> Customer Name: </div>
                          <div class="col-md-7 value"> <?=$customer->name;?> </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5 name"> Email: </div>
                          <div class="col-md-7 value"> <?=$customer->email;?> </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5 name"> Phone Number: </div>
                          <div class="col-md-7 value"> <?=sprintf("(%s)%s", $customer->country_code, $customer->phone);?> </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 col-sm-12">
                    <div class="portlet blue-hoki box">
                      <div class="portlet-title">
                        <div class="caption">
                          <i class="fa fa-cogs"></i>Thông tin nạp game
                        </div>
                      </div>
                      <div class="portlet-body" id="game_account">
                        <div class="row static-info">
                          <div class="col-md-5"> Username: </div>
                          <div class="col-md-7"> <?=$item->username;?></div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5"> Password: </div>
                          <div class="col-md-7"> <?=$item->password;?></div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5 name"> Tên nhân vật: </div>
                          <div class="col-md-7 value"><?=$item->character_name;?></div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5 name"> Platform: </div>
                          <div class="col-md-7 value"> <?=$item->platform;?></div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5 name"> Login method: </div>
                          <div class="col-md-7 value"> <?=$item->login_method;?></div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5 name"> Recover Code: </div>
                          <div class="col-md-7 value"> <?=$item->recover_code;?></div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5 name"> Server: </div>
                          <div class="col-md-7 value"> <?=$item->server;?></div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5 name"> Ghi chú: </div>
                          <div class="col-md-7 value"> <?=$item->note;?></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
              </div>
              <div class="tab-pane" id="images">
                <div class="row">
                  <div class="col-md-6 col-sm-12">
                    <a class="btn red btn-outline sbold" id="before_image">Hình trước</a>
                    <img src="<?=$item->getImageBeforeUrl();?>" id="show_before_image" class="img-responsive">
                  </div>
                  <div class="col-md-6 col-sm-12">
                    <a class="btn red btn-outline sbold" id="after_image">Hình sau</a>
                    <img src="<?=$item->getImageAfterUrl();?>" id="show_after_image" class="img-responsive">
                  </div>
                </div>
              </div>
              <div class="tab-pane" id="complain">
                <!-- Start -->
                <div class="row">
                  <div class="col-md-12">
                    <div class="portlet light portlet-fit bordered">
                      <div class="portlet-title">
                        <div class="caption">
                          <i class="icon-microphone font-green"></i>
                          <span class="caption-subject bold font-green uppercase"> Phản hồi từ khách hàng </span>
                        </div>
                      </div>
                      <div class="portlet-body">
                        <div class="timeline">
                          <?php foreach ($order->complains as $complain):?>
                          <div class="timeline-item">
                            <div class="timeline-badge">
                              <?php if ($complain->sender->avatarImage) :?>
                              <img class="timeline-badge-userpic" src="<?=$complain->sender->getAvatarUrl();?>"> 
                              <?php else : ?>
                                <div class="timeline-icon">
                                  <i class="icon-user-following font-green-haze"></i>
                                </div>
                              <?php endif; ?>
                            </div>
                            <div class="timeline-body">
                              <div class="timeline-body-arrow"> </div>
                              <div class="timeline-body-head">
                                <div class="timeline-body-head-caption">
                                  <a href="javascript:;" class="timeline-body-title font-blue-madison"><?=$complain->sender->name;?></a>
                                  <span class="timeline-body-time font-grey-cascade">Phản hồi vào lúc <?=$complain->created_at;?></span>
                                </div>
                              </div>
                              <div class="timeline-body-content">
                                <span class="font-grey-cascade"><?=$complain->content;?></span>
                              </div>
                            </div>
                          </div>
                          <?php endforeach;?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- End -->
              </div>
            </div>
          </div>
        </div>
      </div>
  </div>
</div>
