<?php 
use backend\models\Order;
$steps = ['1' => Order::STATUS_VERIFYING, '2' => Order::STATUS_PENDING, '3' => Order::STATUS_PROCESSING, '4' => Order::STATUS_COMPLETED];
$step = array_search($order->status, $steps);
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
            <i class="fa fa-check"></i> Processing </span>
            <p style="color: #CCC">Đơn hàng đã thực hiện xong</p> 
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