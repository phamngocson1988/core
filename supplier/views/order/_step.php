<?php 
use supplier\models\OrderSupplier;
$steps = ['1' => OrderSupplier::STATUS_APPROVE, '2' => OrderSupplier::STATUS_PROCESSING, '3' => OrderSupplier::STATUS_COMPLETED];
$step = array_search($order->status, $steps);
$percent = $step * 33.3;
?>
<div class="form-wizard">
    <div class="form-body">
        <ul class="nav nav-pills nav-justified steps">
        <li class="<?php if ($step >= 1) echo 'active' ;?>">
            <a href="javasciprt:;" class="step">
            <span class="number"> 1 </span>
            <span class="desc">
            <i class="fa fa-check"></i> Approve </span>
            <p style="color: #CCC">Đơn hàng đã nhận xử lý</p> 
            </a>
        </li>
        <li class="<?php if ($step >= 2) echo 'active' ;?>">
            <a href="javasciprt:;" class="step">
            <span class="number"> 2 </span>
            <span class="desc">
            <i class="fa fa-check"></i> Processing </span>
            <p style="color: #CCC">Đơn hàng đang thực hiện</p> 
            </a>
        </li>
        <li class="<?php if ($step >= 3) echo 'active' ;?>">
            <a href="javasciprt:;" class="step">
            <span class="number"> 3 </span>
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