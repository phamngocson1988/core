<?php
use yii\helpers\Url;
?>
<div class="tabbable-line">
  <h2><?=$supplier->getName();?></h2><h4>Số dư khả dụng: <?=number_format($supplier->walletTotal());?>
  <ul class="nav nav-tabs ">
      <li class="active">
          <a href="#supplier-order" data-toggle="tab"> Đơn hàng </a>
      </li>
      <li>
          <a href="#withdraw-request" data-toggle="tab"> Yêu cầu rút tiền </a>
      </li>
  </ul>
  <div class="tab-content">
      <div class="tab-pane active" id="supplier-order">
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th> Mã đơn </th>
              <th> Ngày hoàn thành </th>
              <th> Số gói </th>
              <th> Số tiền nhận được </th>
              <th> Trạng thái </th>
            </tr>
          </thead>
          <tbody>
            <?php if (!count($orders)) : ?>
            <tr><td colspan="5">không có dữ liệu</td></tr>
            <?php else : ?>
            <?php foreach ($orders as $order) : ?>
            <tr>
              <td><a href="<?=Url::to(['order/view', 'id' => $order['id']]);?>" target="_blank"><?=$order['id'];?></a></td>
              <td><?=$order['completed_at'];?></td>
              <td><?=$order['quantity'];?></td>
              <td><?=$order['total_price'];?></td>
              <td><?=$order['status'];?></td>
            </tr>
            <?php endforeach;?>
            <?php endif;?>
          </tbody>
          <tfoot>
            <td colspan="2"></td>
            <td><?=number_format($totalQuantity);?></td>
            <td><?=number_format($totalAmount);?></td>
            <td></td>
          </tfoot>
        </table>
      </div>
      <div class="tab-pane" id="withdraw-request">
          <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th> Mã yêu cầu </th>
              <th> Ngày hoàn thành </th>
              <th> Số tiền nhận được </th>
              <th> Tài khoản nhận </th>
            </tr>
          </thead>
          <tbody>
            <?php if (!count($requests)) : ?>
            <tr><td colspan="4">không có dữ liệu</td></tr>
            <?php else : ?>
            <?php foreach ($requests as $request) : ?>
            <tr>
              <td><?=$request->id;?></td>
              <td><?=$request->done_at;?></td>
              <td><?=number_format($request->amount);?></td>
              <td><?=sprintf("%s (%s)", $request->account_name, $request->bank_code);?></td>
            </tr>
            <?php endforeach;?>
            <?php endif;?>
          </tbody>
          <tfoot>
            <td colspan="2"></td>
            <td><?=number_format($totalWithdraw);?></td>
            <td></td>
          </tfoot>
        </table>
      </div>
  </div>
</div>