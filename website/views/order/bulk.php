<?php
use common\components\helpers\FormatConverter;
use common\components\helpers\StringHelper;
use website\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
$this->registerJsFile('@web/js/complains.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js', ['depends' => ['\yii\web\JqueryAsset']]);
$this->registerJsFile('https://unpkg.com/axios/dist/axios.min.js', ['depends' => ['\yii\web\JqueryAsset']]);

?>
<div id="app">
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
        </tr>
      </thead>
      <tbody>
        <?php if (!$models) : ?>
        <tr><td class="text-center" colspan="8">No data found.</td></tr>
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
          <td>
            <?php if ($order->payment_id) : ?>
          	<?=$order->payment_id;?>
          	<?php else : ?>
            <a href='#' @click="this.chooseId($order->id)" class="btn btn-primary">Submit</a>
          	<?php endif;?>
          </td>
        </tr>
      	<?php endforeach;?>
      </tbody>
    </table>
  </div>
  <!-- END TABLE -->
</div>
<!-- END TABLE -->

<!-- Modal order view-->
<div class="modal fade modal-kg" id="paymentGame" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog  modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
    <div class="modal-header d-block">
      <h2 class="modal-title text-center w-100 text-red text-uppercase">Payment game</h2>
      <p class="text-center d-block">Bulk ID: #{{ id }}</p>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <div class="row">
        <div class="col-md-6 border-right">
          <p><span class="list-item">Game:</span><b>{{ info.game_title }}</b></p>
          <hr />
          <p><span class="list-item">Final Payment:</span><b class="text-red">{{ info.total_amount }} {{ info.currency }}</b></p>
        </div>
        <div class="col-md-6">
          {{ info.payment_data_content }}
        </div>
      </div>
    </div>
    </div>
  </div>
</div>
<!-- end modal order view -->
</div>


<?php
$mappingOrders = json_encode($mappingOrders);
$csrfTokenName = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->csrfToken;
$script = <<< JS
var mappingOrders = $mappingOrders;
var app = new Vue({
  el: '#app',
  data: {
    id: null
  },
  computed: {
    info() {
      return this.id ? mappingOrders[this.id] : {};
    }
  },
  method() {
    chooseId(id) {
      console.log('choose id', id);
      this.id = id;
      $('#paymentGame').modal('show');
    }
  },
  created() {
    console.log('app created')
  }
});
JS;
$this->registerJs($script);
?>
