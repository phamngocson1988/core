<?php
use common\components\helpers\FormatConverter;
use website\widgets\LinkPager;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
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
      	<?php foreach ($verifyingOrders as $order) : ?>
        <tr>
          <th scope="row">
            <a href='<?=Url::to(['order/detail', 'id' => $order->id]);?>' data-target="#detailOrder" data-toggle="modal" >#<?=$order->id;?></a>
            <span class="date-time"><?=FormatConverter::convertToDate(strtotime($order->created_at), 'd-m-Y H:i');?></span>
          </th>
          <td class="text-center"><?=$order->game_title;?></td>
          <td class="text-center"><span class="text-red">$<?=number_format($order->total_price, 1);?></span></td>
          <td class="text-center"><?=number_format($order->quantity, 1);?></td>
          <td class="text-center"><span class="text-red"><?=number_format($order->total_unit);?> <?=$order->unit_name;?></span></td>
          <td class="text-center text-capitalize"><?=$order->getStatusLabel();?></td>
          <td class="text-center">
          	<?php if ($order->payment_id) : ?>
          	<?=$order->payment_id;?>
          	<?php else : ?>
          	<button type="button" class="btn btn-primary">Submit</button>
          	<?php endif;?>
          </td>
          <td class="text-center"><button type="button" class="btn btn-upload">Upload</button></td>
          <td class="text-center"><img class="icon-sm btn-delete" src="/images/icon/criss-cross.svg" /></td>
        </tr>
      	<?php endforeach;?>
      </tbody>
    </table>
  </div>
  <!-- END TABLE -->

  <hr class="my-5" />
  <div class="d-flex bd-highlight justify-content-between align-items-center orders-history-wrapper mb-3">
    <p class="lead mb-0">Orders history</p>
			<?php $form = ActiveForm::begin(['action' => ['order/index'], 'method' => 'get', 'options' => ['class' => 'd-flex ml-auto']]);?>
			<?=$form->field($search, 'start_date', [
				'options' => ['class' => 'flex-fill d-flex align-items-center mr-3'],
				'labelOptions' => ['class' => 'd-block w-100 mr-2 mb-0'],
				'inputOptions' => ['class' => 'form-control', 'type' => 'date', 'name' => 'start_date'],
				'template' => '{label}{input}'
			])->textInput();?>
      <?=$form->field($search, 'end_date', [
				'options' => ['class' => 'flex-fill d-flex align-items-center mr-3'],
				'labelOptions' => ['class' => 'd-block w-100 mr-2 mb-0'],
				'inputOptions' => ['class' => 'form-control', 'type' => 'date', 'name' => 'end_date'],
				'template' => '{label}{input}'
			])->textInput();?>
			<?=$form->field($search, 'status', [
				'options' => ['class' => 'flex-fill d-flex align-items-center mr-3'],
				'labelOptions' => ['class' => 'd-block w-100 mr-2 mb-0'],
				'inputOptions' => ['class' => 'form-control', 'name' => 'status'],
				'template' => '{label}{input}'
			])->dropdownList($search->fetchStatusList(), ['prompt' => 'Select status']);?>
      <div class="flex-fill">
        <button type="submit" class="btn btn-primary">Filter</button>
      </div>
			<?php ActiveForm::end()?>
    <!-- </div> -->
  </div>

  <div class="table-wrapper table-responsive ">
    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">No</th>
          <th class="text-center" scope="col">Game</th>
          <th class="text-center" scope="col">Amount</th>
          <th class="text-center" scope="col">Quantity</th>
          <th class="text-center" scope="col">Unit</th>
          <th class="text-center" scope="col">Status</th>
          <th scope="col">Bank invoice</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
      	<?php foreach ($orders as $order) : ?>
        <tr>
          <th scope="row">
            <a href="#">#<?=$order->id;?></a>
            <span class="date-time"><?=FormatConverter::convertToDate(strtotime($order->created_at), 'd-m-Y H:i');?></span>
          </th>
          <td class="text-center"><?=$order->game_title;?></td>
          <td class="text-center"><span class="text-red">$<?=number_format($order->total_price, 1);?></span></td>
          <td class="text-center"><?=number_format($order->quantity, 1);?></td>
          <td class="text-center"><span class="text-red"><?=sprintf("%s %s", number_format($order->total_unit), $order->unit_name);?></span></td>
          <td class="text-center">
            <?=$order->getStatusLabel();?>
            <?php $percent = $order->getPercent();?>
            <div class="progress">
              <div class="progress-bar bg-info" role="progressbar" style="width: <?=$percent;?>%;" aria-valuenow="<?=$percent;?>"
                aria-valuemin="0" aria-valuemax="100"><?=$percent;?>%</div>
            </div>
          </td>
          <td class="text-center"><a class="text-red" href="<?=Url::to(['order/view', 'id' => $order->id]);?>">View+</a></td>
          <td class="text-center"><img class="icon-sm btn-delete" src="/images/icon/trash-can.svg"></td>
        </tr>
      	<?php endforeach;?>
        <tr>
          <th scope="row" class="text-left">TOTAL</th>
          <td class="text-center"></td>
          <td class="text-center"><b class="text-red">$<?=number_format($search->getCommand()->sum('total_price'), 1);?></b></td>
          <td class="text-center"><b class="text-red"><?=number_format($search->getCommand()->sum('quantity'), 1);?></b></td>
          <td class="text-center" colspan="4"></td>
        </tr>
      </tbody>
    </table>
  </div>
  <nav aria-label="Page navigation" class="mt-2 mb-5">
    <?=LinkPager::widget(['pagination' => $pages]);?>
  </nav>
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
<?php
$script = <<< JS
$('#detailOrder').on('show.bs.modal', function (e) {
    $(this).find('.modal-content').load(e.relatedTarget.href);
});
JS;
$this->registerJs($script);
?>