<?php
use common\components\helpers\FormatConverter;
use common\components\helpers\StringHelper;
use website\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
$this->registerJsFile('@web/js/complains.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

?>
<div class="container order-page">
  <h1 class="text-uppercase mt-5">my order</h1>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="#">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">My orders</li>
    </ol>
  </nav>
  <p class="lead mb-2">Verifying Orders</p>
  <div class="table-wrapper table-responsive">
    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">No</th>
          <th class="text-center" scope="col">Game</th>
          <th class="text-center" scope="col">Amount</th>
          <th class="text-center" scope="col">Quantity</th>
          <th class="text-center" scope="col">Unit</th>
          <th class="text-center" scope="col">Status</th>
          <th class="text-center" scope="col">Transaction number</th>
          <th class="text-center" scope="col">Bank invoice</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!$models) : ?>
        <tr><td class="text-center" colspan="9">No data found.</td></tr>
        <?php endif;?>
      	<?php foreach ($models as $order) : ?>
        <tr>
          <td scope="row">
            <a href='<?=Url::to(['order/view', 'id' => $order->id]);?>' id="<?=$order->id;?>" data-target="#paymentGame" data-toggle="modal" >#<?=$order->id;?></a>
            <span class="date-time"><?=FormatConverter::convertToDate(strtotime($order->created_at), 'd-m-Y H:i');?></span>
          </td>
          <td><?=$mappingOrders[$order->id]['game_title'];?></td>
          <td><?=$order->kingcoin;?></td>
          <td><?=$mappingOrders[$order->id]['quantity'];?></td>
          <td><?=$mappingOrders[$order->id]['total_unit'];?></td>
          <td><?=$order->status;?></td>
          <td><?=$order->payment_id;?></td>
          <td><?=$order->payment_id;?></td>
          <td>xxx</td>
        </tr>
      	<?php endforeach;?>
      </tbody>
    </table>
  </div>
  <!-- END TABLE -->
</div>
<!-- END TABLE -->

<!-- Modal order detail-->
<div class="modal fade modal-kg" id="detailOrder" tabindex="-1" role="dialog"
  aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog  modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      
    </div>
  </div>
</div>
<!-- end modal order detail -->
