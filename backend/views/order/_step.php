<?php 
use backend\models\Order;
use yii\helpers\ArrayHelper;

$steps = [Order::STATUS_VERIFYING => 1, Order::STATUS_PENDING => 2, Order::STATUS_PROCESSING => 3, Order::STATUS_PARTIAL => 3, Order::STATUS_COMPLETED => 4];
$step = ArrayHelper::getValue($steps, $order->status);
$percent = $step * 25;
?>
<div class="form-wizard">
    <div class="form-body">
        <ul class="nav nav-pills nav-justified steps">
        <li class="<?php if ($step >= 1) echo 'active' ;?>">
            <a href="javasciprt:;" class="step">
            <span class="number"> 1 </span>
            <span class="desc">
            <i class="fa fa-check"></i> Verifying </span>
            <p style="color: #CCC">Đơn hàng chưa thanh toán</p> 
            </a>
        </li>
        <li class="<?php if ($step >= 2) echo 'active' ;?>">
            <a href="javasciprt:;" class="step">
            <span class="number"> 2 </span>
            <span class="desc">
            <i class="fa fa-check"></i> Pending </span>
            <p style="color: #CCC">Đơn hàng đã thanh toán</p> 
            </a>
        </li>
        <li class="<?php if ($step >= 3) echo 'active' ;?>">
            <a href="javasciprt:;" class="step">
            <span class="number"> 3 </span>
            <span class="desc">
            <?php if ($order->isProcessingOrder()) : ?>
            <i class="fa fa-check"></i> Processing </span>
            <p style="color: #CCC">Đơn hàng đang thực hiện</p>
            <?php elseif ($order->isPartialOrder()) : ?>
            <i class="fa fa-check"></i> Partial </span>
            <p style="color: #CCC">Đơn hàng đã thực hiện một phần</p>
            <?php endif;?> 
            </a>
        </li>
        <li class="<?php if ($step >= 4) echo 'active' ;?>">
            <a href="javasciprt:;" class="step">
            <span class="number"> 4 </span>
            <span class="desc">
            <i class="fa fa-check"></i> Completed </span>
            <p style="color: #CCC">Đơn hàng đã hoàn tất</p> 
            </a>
        </li>
        </ul>
        <div id="bar" class="progress progress-striped" role="progressbar">
        <div class="progress-bar progress-bar-success" style="width: <?=$percent;?>%"> </div>
        </div>
    </div>
</div>