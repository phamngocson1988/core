<?php 
use backend\models\Promotion;
use yii\helpers\Url;
?>
<!-- BEGIN SIDEBAR -->
<div class="page-sidebar-wrapper">
  <div class="page-sidebar navbar-collapse collapse">
    <?php if (array_key_exists('main_menu_active', $this->params)) {
        $main_menu_active = $this->params['main_menu_active'];
    } else {
        $main_menu_active = 'dashboard';
    }
    ?>
    <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px" main_menu_active='<?=$main_menu_active;?>'>
      <li class="sidebar-toggler-wrapper hide">
        <div class="sidebar-toggler">
          <span></span>
        </div>
      </li>
      <li class="sidebar-search-wrapper">
      </li>


      <!-- Bảng thông báo -->
      <li class="nav-item start active open">
        <a href="<?=Url::to(['site/index']);?>" class="nav-link nav-toggle" code='dashboard'>
          <i class="icon-home"></i>
          <span class="title"><?=Yii::t('app', 'dashboard');?></span>
          <span class="selected"></span>
          <span class="arrow open"></span>
        </a>
      </li>

      <!-- Ban quản trị -->
      <?php if (Yii::$app->user->can('hr')) : ?>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-lock"></i>
        <span class="title"><?=Yii::t('app', 'user');?></span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item">
            <a href="<?=Url::to(['rbac/index']);?>" class="nav-link " code='rbac.index'>
            <span class="title">Nhà quản trị</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?=Url::to(['rbac/role']);?>" class="nav-link" code='rbac.role'>
            <span class="title"><?=Yii::t('app', 'role');?></span>
            </a>
          </li>
        </ul>
      </li>
      <?php endif;?>

      <!-- Khách hàng -->
      <?php if (Yii::$app->user->cans(['saler', 'customer_support'])) : ?>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-user-following"></i>
        <span class="title">Khách hàng</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="<?=Url::to(['user/index']);?>" class="nav-link " code='user.index'>
            <span class="title">Tất cả</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="<?=Url::to(['user/no-order']);?>" class="nav-link " code='user.no-order'>
            <span class="title">Chưa có giao dịch</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="<?=Url::to(['lead-tracker/index']);?>" class="nav-link " code='lead-tracker.index'>
            <span class="title">Lead Tracker</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="<?=Url::to(['customer-tracker/index']);?>" class="nav-link " code='customer-tracker.index'>
            <span class="title">Customer Tracker</span>
            </a>
          </li>
        </ul>
      </li>
      <?php endif;?>

      <!-- Reseller -->
      <?php if (Yii::$app->user->cans(['saler', 'customer_support'])) : ?>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
          <i class="fa fa-link"></i>
          <span class="title">Reseller</span>
          <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <?php if (Yii::$app->user->cans(['saler', 'customer_support'])) : ?>
          <li class="nav-item  ">
            <a href="<?=Url::to(['reseller/index']);?>" class="nav-link " code='reseller.index'>
            <span class="title">Danh sách reseller</span>
            </a>
          </li>
          <?php endif;?>
        </ul>
      </li>
      <?php endif;?>

      <!-- Supplier -->
      <?php if (Yii::$app->user->cans(['accounting', 'orderteam']) && (Yii::$app->user->id != 189)) : ?>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
          <i class="fa fa-link"></i>
          <span class="title">Nhà cung cấp</span>
          <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="<?=Url::to(['supplier/index']);?>" class="nav-link " code='supplier.index'>
            <span class="title">Danh sách</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="<?=Url::to(['supplier/withdraw-request']);?>" class="nav-link " code='supplier.withdraw-request'>
            <span class="title">Yêu cầu rút tiền</span>
            <?php if ($this->params['withdraw_request']) : ?>
            <span class="badge badge-success"><?=$this->params['withdraw_request'];?></span>
            <?php endif;?>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="<?=Url::to(['supplier/suggest']);?>" class="nav-link " code='supplier.suggest'>
            <span class="title">Yêu cầu game mới</span>
            </a>
          </li>
        </ul>
      </li>
      <?php endif;?>

      <!-- Affiliate -->
      <?php if (Yii::$app->user->cans(['sale_manager', 'customer_support_vip'])) : ?>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
          <i class="fa fa-link"></i>
          <span class="title">Affiliate</span>
          <?php if ($this->params['new_affiliate_request']) : ?>
          <span class="badge badge-success"><?=$this->params['new_affiliate_request'];?></span>
          <?php else : ?>
          <span class="arrow"></span>
          <?php endif;?>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="<?=Url::to(['affiliate/index']);?>" class="nav-link " code='affiliate.index'>
            <span class="title">Tất cả</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="<?=Url::to(['affiliate/request']);?>" class="nav-link " code='affiliate.request'>
            <span class="title">Yêu cầu hợp tác</span>
            <span class="badge badge-success"><?=$this->params['new_affiliate_request'];?></span>
            </a>
          </li>
          <?php if (Yii::$app->user->can('admin')) : ?>
          <li class="nav-item  ">
            <a href="<?=Url::to(['affiliate/withdraw']);?>" class="nav-link " code='affiliate.withdraw'>
            <span class="title">Yêu cầu rút tiền</span>
            <span class="badge badge-success"><?=$this->params['new_commission_withdraw'];?></span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="<?=Url::to(['setting/affiliate_program']);?>" class="nav-link " code='affiliate.setting'>
            <span class="title">Cài đặt</span>
            </a>
          </li>
          <?php endif;?>
        </ul>
      </li>
      <?php endif;?>

      <!-- Bài viết -->
      <?php if (Yii::$app->user->cans(['saler', 'marketing_officer'])) : ?>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-note"></i>
        <span class="title"><?=Yii::t('app', 'posts');?></span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="<?=Url::to(['post/index']);?>" class="nav-link " code='post.index'>
            <span class="title"><?=Yii::t('app', 'posts');?></span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="<?=Url::to(['category/index']);?>" class="nav-link " code='category.index'>
            <span class="title"><?=Yii::t('app', 'categories');?></span>
            </a>
          </li>
        </ul>
      </li>
      <?php endif;?>

      <!-- Trung tâm hổ trợ -->
      <?php if (Yii::$app->user->cans(['saler', 'marketing_officer'])) : ?>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-question"></i>
        <span class="title">Trung tâm hỗ trợ</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="<?=Url::to(['question/index']);?>" class="nav-link " code='question.index'>
            <span class="title">Trung tâm hỗ trợ</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="<?=Url::to(['question/category']);?>" class="nav-link " code='question.category'>
            <span class="title"><?=Yii::t('app', 'categories');?></span>
            </a>
          </li>
        </ul>
      </li>
      <?php endif;?>

      <!-- Kingcoin -->
      <?php if (Yii::$app->user->can('sale_manager')) : ?>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
          <i class="icon-tag"></i>
          <span class="title"><?=Yii::t('app', 'pricing_coin');?></span>
          <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="<?=Url::to(['pricing-coin']);?>" class="nav-link " code='coin.index'>
            <span class="title"><?=Yii::t('app', 'pricing_coin');?></span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="<?=Url::to(['pricing-coin/create']);?>" class="nav-link " code='coin.create'>
            <span class="title"><?=Yii::t('app', 'add_new');?></span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="<?=Url::to(['promotion' ]);?>romotion_scenario=Promotion::SCENARIO_BUY_COIN}" class="nav-link " code='package.promotion'>
            <span class="title">Khuyến mãi</span>
            </a>
          </li>
        </ul>
      </li>
      <?php endif;?>

      <!-- System log -->
      <?php if (Yii::$app->user->can('admin')) : ?>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-settings"></i>
        <span class="title"><?=Yii::t('app', 'system_logs');?></span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="<?=Url::to(['system-log/index']);?>" class="nav-link " code='system-log.index'>
            <span class="title"><?=Yii::t('app', 'system_logs');?></span>
            </a>
          </li>
        </ul>
      </li>
      <?php endif;?>

      <!-- Ví Kingcoin -->
      <?php if (Yii::$app->user->can('admin')) : ?>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-wallet"></i>
        <span class="title">Ví Kingcoin</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="<?=Url::to(['allet/index']);?>" class="nav-link " code='wallet.index'>
            <span class="title">Tất cả</span>
            </a>
          </li>
        </ul>
      </li>
      <?php endif;?>

      <!-- Giao dịch nạp tiền -->
      <?php if (Yii::$app->user->cans(['accounting', 'saler', 'customer_support'])) : ?>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-wallet"></i>
        <span class="title">Giao dịch nạp tiền</span>
        <?php if ($this->params['payment_commitment'] || $this->params['payment_reality']) : ?>
        <span class="badge badge-success"><?=$this->params['payment_commitment'] + $this->params['payment_reality'];?></span>
        <?php else : ?>
        <span class="arrow"></span>
        <?php endif;?>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="<?=Url::to(['payment-transaction/index']);?>" class="nav-link " code='transaction.index'>
            <span class="title">Tất cả</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="<?=Url::to(['payment-transaction/trash']);?>" class="nav-link " code='transaction.trash'>
            <span class="title">Thùng rác</span>
            </a>
          </li>

          <li class="nav-item  ">
            <a href="<?=Url::to(['payment-reality/index']);?>" class="nav-link " code='payment_reality.index'>
            <span class="title">Hóa đơn nhận tiền</span>
            <?php if ($this->params['payment_reality']) : ?>
            <span class="badge badge-success"><?=$this->params['payment_reality'];?></span>
            <?php endif;?>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="<?=Url::to(['payment-commitment/index']);?>" class="nav-link " code='payment_commitment.index'>
            <span class="title">Lịch sử giao dịch</span>
            <?php if ($this->params['payment_commitment']) : ?>
            <span class="badge badge-success"><?=$this->params['payment_commitment'];?></span>
            <?php endif;?>
            </a>
          </li>
        </ul>
      </li>
      <?php endif;?>

      <!-- Cấu hình -->
      <?php if (Yii::$app->user->cans(['saler', 'marketing_officer', 'accounting'])) : ?>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-settings"></i>
        <span class="title">Cấu hình</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <?php if (Yii::$app->user->can('admin')) : ?>
          <li class="nav-item  ">
            <a href="<?=Url::to(['setting/application']);?>" class="nav-link " code='setting.application'>
            <span class="title">Thiết lập nâng cao</span>
            </a>
          </li>
          <?php endif;?>
          <?php if (Yii::$app->user->cans(['saler', 'marketing_officer'])) : ?>
          <li class="nav-item  ">
            <a href="<?=Url::to(['hotnew/index']);?>" class="nav-link " code='hotnew.index'>
            <span class="title">What's hot news</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="<?=Url::to(['setting/top_notice']);?>" class="nav-link " code='setting.top_notice'>
            <span class="title">Thông báo ở đầu trang</span>
            </a>
          </li>
          <?php endif;?>
          <?php if (Yii::$app->user->cans(['sale_manager', 'marketing_officer'])) : ?>
          <li class="nav-item  ">
            <a href="<?=Url::to(['setting/social']);?>" class="nav-link " code='setting.social'>
            <span class="title"><?=Yii::t('app', 'social_networks');?></span>
            </a>
          </li>
          <?php endif;?>
          <!-- <li class="nav-item  ">
            <a href="<?=Url::to(['setting/script']);?>" class="nav-link " code='setting.script'>
            <span class="title"><?=Yii::t('app', 'header_scripts');?></span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="<?=Url::to(['setting/import']);?>" class="nav-link " code='setting.import'>
            <span class="title">Imports</span>
            </a>
          </li> -->
          <?php if (Yii::$app->user->cans(['saler', 'marketing_officer'])) : ?>
          <li class="nav-item  ">
            <a href="<?=Url::to(['setting/gallery']);?>" class="nav-link " code='setting.gallery'>
            <span class="title">Home banner</span>
            </a>
          </li>
          <?php endif;?>
          <?php if (Yii::$app->user->can('sale_manager')) : ?>
          <li class="nav-item  ">
            <a href="<?=Url::to(['order-complain']);?>" class="nav-link " code='ordercomplain.index'>
            <span class="title">Mẫu phản hồi</span>
            </a>
          </li>
          <?php endif;?>
          
          <?php if (Yii::$app->user->can('accounting')) : ?>
          <li class="nav-item  ">
            <a href="<?=Url::to(['currency/index']);?>" class="nav-link" code='currency.index'>
            <span class="title">Quản lý tiền tệ</span>
            </a>
          </li>
          <?php endif;?>
          <?php if (Yii::$app->user->can('accounting')) : ?>
          <li class="nav-item  ">
            <a href="javascript:;" class="nav-link nav-toggle">
            <span class="title">Cổng thanh toán</span><span class="arrow"></span>
            </a>
            <ul class="sub-menu">
              <li class="nav-item">
                <a href="<?=Url::to(['aygate/index']);?>" class="nav-link" code='paygate.index'>
                  <i class="cc-paypal"></i> Cổng offline
                </a>
              </li>
            </ul
          </li>
          <?php endif;?>
        </ul>
      </li>
      <?php endif;?>

      <!-- Game -->
      <?php if (Yii::$app->user->cans(['saler', 'orderteam', 'marketing_officer', 'customer_support'])) : ?>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-note"></i>
        <span class="title"><?=Yii::t('app', 'games');?></span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  "> 
            <a href="<?=Url::to(['game/index']);?>" class="nav-link " code='game.index'>
            <span class="title">Shop Game</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="<?=Url::to(['game-category/index']);?>" class="nav-link " code='game-category.index'>
            <span class="title">Danh mục game</span>
            </a>
          </li>
          <?php if (Yii::$app->user->cans(['saler', 'marketing_officer', 'customer_support'])) : ?>
          <li class="nav-item  "> 
            <a href="<?=Url::to(['flashsale/index']);?>" class="nav-link " code='flashsale.index'>
            <span class="title">Flash sale</span>
            </a>
          </li>
          <?php endif;?>
          <?php if (Yii::$app->user->cans(['accounting', 'saler'])) : ?>
          <li class="nav-item  "> 
            <a href="<?=Url::to(['reseller-price/index']);?>" class="nav-link " code='reseller-price.index'>
            <span class="title">Giá Reseller</span>
            </a>
          </li>
          <?php endif;?>
          <?php if (Yii::$app->user->can('admin')) : ?>
          <li class="nav-item  ">
            <a href="<?=Url::to(['ame-profit/index']);?>" class="nav-link " code='game-profit.index'>
            <span class="title">Lợi nhuận chuẩn</span>
            </a>
          </li>
          <?php endif;?>
          <?php if (Yii::$app->user->can('sale_manager')) : ?>
          <li class="nav-item  ">
            <a href="<?=Url::to(['promotion', 'promotion_scenario'=>Promotion::SCENARIO_BUY_GEMS]);?>" class="nav-link " code='game.promotion'>
            <span class="title">Khuyến mãi</span>
            </a>
          </li>
          <?php endif;?>
          <?php if (Yii::$app->user->can('saler')) : ?>
          <li class="nav-item  ">
            <a href="<?=Url::to(['game/log']);?>" class="nav-link " code='game.log'>
            <span class="title">Lịch sử thay đổi giá</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="<?=Url::to(['game/price']);?>" class="nav-link " code='game.price'>
            <span class="title">Tổng hợp giá bán</span>
            </a>
          </li>
          <?php endif;?>
        </ul>
      </li>
      <?php endif;?>

      <!-- Đơn hàng -->
      <?php if (Yii::$app->user->cans(['saler', 'orderteam', 'accounting', 'customer_support'])) : ?>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-basket"></i>
        <span class="title">Quản lý đơn hàng</span>
        <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <li class="nav-item  ">
            <a href="<?=Url::to(['order/index']);?>" class="nav-link " code='order.index'>
            <span class="title">Đơn hàng</span>
            </a>
          </li>
          <?php if (Yii::$app->user->can('saler') || Yii::$app->user->can('accounting')) : ?>
          <?php endif;?>
          <li class="nav-item  ">
            <a href="<?=Url::to(['order/pending']);?>" class="nav-link " code='order.pending'>
            <span class="title">Đơn hàng pending</span>
            <span class="badge badge-success"><?=$this->params['new_pending_order'];?></span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="<?=Url::to(['order/pending-information']);?>" class="nav-link " code='order.pendinginformation'>
            <span class="title">Pending Information</span>
            <span class="badge badge-success"><?=$this->params['new_pending_info_order'];?></span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="<?=Url::to(['order/processing']);?>" class="nav-link " code='order.processing'>
            <span class="title">Đơn hàng processing</span>
            <span class="badge badge-success"><?=$this->params['processing_order'];?></span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="<?=Url::to(['order/partial']);?>" class="nav-link " code='order.partial'>
            <span class="title">Đơn hàng completed (P)</span>
            <span class="badge badge-success"><?=$this->params['partial_order'];?></span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="<?=Url::to(['order/completed']);?>" class="nav-link " code='order.completed'>
            <span class="title">Đơn hàng completed</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="<?=Url::to(['order/confirmed']);?>" class="nav-link " code='order.confirmed'>
            <span class="title">Đơn hàng confirmed</span>
            </a>
          </li>
          <li class="nav-item  ">
            <a href="<?=Url::to(['order/cancel']);?>" class="nav-link " code='order.cancel'>
            <span class="title">Đơn hàng cancel</span>
            <span class="badge badge-success"><?=$this->params['cancelling_order'];?></span>
            </a>
          </li>
          <?php if (Yii::$app->user->can('sale_manager')) : ?>
          <li class="nav-item  ">
            <a href="<?=Url::to(['order/feedback-order']);?>" class="nav-link " code='order.feedback'>
            <span class="title">Đơn hàng có feedback</span>
            </a>
          </li>
          <?php endif;?>
          <?php if (Yii::$app->user->can('accounting') || Yii::$app->user->can('saler')) : ?>
          <li class="nav-item  ">
            <a href="<?=Url::to(['order-log/index']);?>" class="nav-link " code='order.log'>
            <span class="title">Log đơn hàng</span>
            </a>
          </li>
          <?php endif;?>
        </ul>
      </li>
      <?php endif;?>

      <!-- Thống kê -->
      <?php if (Yii::$app->user->cans(['admin', 'accounting', 'saler', 'orderteam', 'customer_support', 'saler', 'sale_manager'])) : ?>
      <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">
          <i class="fa fa-line-chart"></i>
          <span class="title">Thống kê & báo cáo</span>
          <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
          <?php if (Yii::$app->user->cans(['admin', 'accounting', 'saler', 'orderteam', 'orderteam_manager'])) : ?>
          <li class="nav-item  ">
            <a href="<?=Url::to(['order/report']);?>" class="nav-link nav-toggle" code='order.report'>
              <span class="title">Thống kê đơn hàng</span>
            </a>
          </li>
          <?php endif;?>
          <?php if (Yii::$app->user->cans(['accounting', 'customer_support', 'saler'])) : ?>
          <li class="nav-item  ">
            <a href="javascript:;" class="nav-link nav-toggle">
              <span class="title">Thống kê dòng tiền</span>
              <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
              <?php if (Yii::$app->user->can('accounting')) : ?>
              <li class="nav-item  ">
                <a href="<?=Url::to(['report/finance-transaction']);?>" class="nav-link nav-toggle" code='report.finance.transaction'>
                  <span class="title">Giao dịch nạp tiền</span>
                </a>
              </li>
              <?php endif;?>
              <li class="nav-item  ">
                <a href="<?=Url::to(['report/finance-balance']);?>" class="nav-link nav-toggle" code='report.finance.balance'>
                  <span class="title">Số dư tài khoản khách hàng</span>
                </a>
              </li>
              <?php if (Yii::$app->user->can('accounting')) : ?>
              <li class="nav-item  ">
                <a href="<?=Url::to(['supplier/balance']);?>" class="nav-link nav-toggle" code="supplier.balance">
                  <span class="title">Số dư tài khoản nhà cung cấp</span>
                </a>
              </li> 
              <?php endif;?>
            </ul>
          </li>
          <?php endif;?>
          <?php if (Yii::$app->user->can('sale_manager') || Yii::$app->user->can('orderteam')) : ?>
          <li class="nav-item  ">
            <a href="javascript:;" class="nav-link nav-toggle">
              <span class="title">Thống kê thực hiện đơn hàng</span>
              <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
              <?php if (Yii::$app->user->can('orderteam')) : ?>
              <li class="nav-item  ">
                <a href="<?=Url::to(['report/process-order']);?>" class="nav-link nav-toggle" code="report.process.order">
                  <span class="title">Theo đơn hàng</span>
                </a>
              </li>
              <?php endif;?>
              <?php if (Yii::$app->user->can('sale_manager') || Yii::$app->user->can('orderteam_manager')) : ?>
              <li class="nav-item  ">
                <a href="<?=Url::to(['report/process-game']);?>" class="nav-link nav-toggle" code="report.process.game">
                  <span class="title">Theo game</span>
                </a>
              </li>
              <?php endif;?>
              <?php if (Yii::$app->user->can('sale_manager') || Yii::$app->user->can('orderteam_manager')) : ?>
              <li class="nav-item  ">
                <a href="javascript:;" class="nav-link nav-toggle">
                  <span class="title">Theo reseller</span>
                </a>
              </li>
              <?php endif;?>
              <?php if (Yii::$app->user->can('orderteam_manager')) : ?>
              <li class="nav-item  ">
                <a href="<?=Url::to(['report/process-supplier']);?>" class="nav-link nav-toggle" code="report.process.supplier">
                  <span class="title">Theo nhà cung cấp</span>
                </a>
              </li>
              <?php endif;?>
              <?php if (Yii::$app->user->can('orderteam')) : ?>
              <li class="nav-item  ">
                <a href="<?=Url::to(['report/process-user']);?>" class="nav-link nav-toggle" code="report.process.user">
                  <span class="title">Theo nhân viên</span>
                </a>
              </li>
              <?php endif;?>
            </ul>
          </li>
          <?php endif;?>
          <?php if (Yii::$app->user->can('saler')) : ?>
          <li class="nav-item  ">
            <a href="javascript:;" class="nav-link nav-toggle">
              <span class="title">Thống kê bán hàng</span>
              <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
              <?php if (Yii::$app->user->can('orderteam_manager')) : ?>
              <li class="nav-item  ">
                <a href="<?=Url::to(['report/sale-order']);?>" class="nav-link " code='report.sale.order'>
                  <span class="title">Doanh số theo đơn hàng</span>
                </a>
              </li>
              <li class="nav-item  ">
                <a href="<?=Url::to(['report/sale-game']);?>" class="nav-link " code='report.sale.game'>
                  <span class="title">Doanh số theo game</span>
                </a>
              </li>
              <?php endif;?>
              <li class="nav-item  ">
                <a href="<?=Url::to(['report/sale-user']);?>" class="nav-link " code='report.sale.user'>
                  <span class="title">Doanh số theo nhân viên</span>
                </a>
              </li>
              <li class="nav-item  ">
                <a href="<?=Url::to(['report/sale-reseller']);?>" class="nav-link " code='report.sale.reseller'>
                  <span class="title">Doanh số theo reseller</span>
                </a>
              </li>
            </ul>
          </li>
          <?php endif;?>
          <?php if (Yii::$app->user->can('accounting')) : ?>
          <li class="nav-item  ">
            <a href="javascript:;" class="nav-link nav-toggle">
              <span class="title">Thống kê chi phí lợi nhuận</span>
              <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
              <li class="nav-item  ">
                <!--<a href="<?=Url::to(['report/cost-order']);?>" class="nav-link nav-toggle" code='report.cost.order'>
                  <span class="title">Theo đơn hàng</span>
                </a> -->
                <a href="<?=Url::to(['report-profit/order']);?>" class="nav-link nav-toggle" code='report.cost.order'>
                  <span class="title">Theo đơn hàng</span>
                </a>
              </li>
              <li class="nav-item  ">
                <a href="<?=Url::to(['report-profit/game']);?>" class="nav-link nav-toggle" code='report.cost.game'>
                  <span class="title">Theo game</span>
                </a>
              </li>
              <li class="nav-item  ">
                <a href="<?=Url::to(['report-profit/user']);?>" class="nav-link nav-toggle" code='report.cost.user'>
                  <span class="title">Theo nhân viên bán hàng</span>
                </a>
              </li>
              <li class="nav-item  ">
                <a href="<?=Url::to(['report/cost-supplier']);?>" class="nav-link nav-toggle" code='report.cost.supplier'>
                  <span class="title">Theo nhà cung cấp</span>
                </a>
              </li>
              <li class="nav-item  ">
                <a href="<?=Url::to(['supplier/fast-report']);?>" class="nav-link nav-toggle" code='supplier.fast-report'>
                  <span class="title">Báo cáo nhanh</span>
                </a>
              </li>
              <li class="nav-item  ">
                <a href="<?=Url::to(['report/cost-reseller']);?>" class="nav-link nav-toggle" code='report.cost.reseller'>
                  <span class="title">Theo reseller</span>
                </a>
              </li>
            </ul>
          </li>
          <?php endif;?>
          <li class="nav-item  ">
          <a href="<?=Url::to(['report-commission/index']);?>" class="nav-link nav-toggle" code='report.commission.index'>
            <span class="title">Theo hoa hồng</span>
          </a>
          </li>
        </ul>
      </li>
      <?php endif;?>
    </ul>
  </div>
</div>