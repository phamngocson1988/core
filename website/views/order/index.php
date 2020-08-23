<?php
use common\components\helpers\FormatConverter;
use website\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
$this->registerJsFile('@web/js/complains.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$orderIds = ArrayHelper::getColumn($orders, 'id');
$orderIds = implode(',', $orderIds);
$checkNewMessageUrl = Url::to(['order/check-new-message', 'ids' => $orderIds])
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
        <?php if (!$verifyingOrders) : ?>
        <tr><td class="text-center" colspan="9">No data found.</td></tr>
        <?php endif;?>
      	<?php foreach ($verifyingOrders as $order) : ?>
        <tr>
          <th scope="row">
            <a href='<?=Url::to(['order/view', 'id' => $order->id]);?>' id="<?=$order->id;?>" data-target="#paymentGame" data-toggle="modal" >#<?=$order->id;?></a>
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
            <a href='<?=Url::to(['order/view', 'id' => $order->id]);?>' data-target="#paymentGame" data-toggle="modal" class="btn btn-primary">Submit</a>
          	<?php endif;?>
          </td>
          <td class="text-center">
            <?php if ($order->evidence) : ?>
            <a href='<?=$order->evidence;?>' target="_blank">View</a>
            <?php endif;?>
            <a href='<?=Url::to(['order/view', 'id' => $order->id]);?>' data-target="#paymentGame" data-toggle="modal" class="btn btn-upload">Upload</a>
          </td>
          <td class="text-center">
            <?php if ($order->request_cancel) : ?>
              Waiting cancellation
            <?php else :?>
            <a href="<?=Url::to(['order/cancel', 'id' => $order->id]);?>" class='cancel-order-button'><img class="icon-sm btn-delete" src="/images/icon/criss-cross.svg" /></a>
            <?php endif;?>
          </td>
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
        <?php if (!$orders) : ?>
        <tr><td class="text-center" colspan="8">No data found.</td></tr>
        <?php endif;?>
      	<?php foreach ($orders as $order) : ?>
        <tr>
          <th scope="row">
            <div class="media">
              <div class="media-body">
                <a href="<?=Url::to(['order/detail', 'id' => $order->id]);?>" data-toggle="modal" data-target="#detailOrder" id="<?=$order->id;?>">#<?=$order->id;?></a>
                <span class="date-time"><?=FormatConverter::convertToDate(strtotime($order->created_at), 'd-m-Y H:i');?></span>
              </div>
              <img class="align-self-center ml-1 icon-sm <?=$order->hasNewMessage() ? '' : 'd-none';?>" src="https://image.flaticon.com/icons/svg/497/497738.svg" alt="There are new messages" id="newMessageAlert<?=$order->id;?>">
            </div>        
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
          <td class="text-center">
            <?php if ($order->evidence) : ?>
            <a href='<?=$order->evidence;?>' class="text-red" target="_blank">View+</a>
            <?php endif;?>
          </td>
          <td class="text-center">
            <?php if ($order->isPendingOrder() || $order->isProcessingOrder() || $order->isPartialOrder()) : ?>
            <?php if ($order->request_cancel) : ?>
              Waiting cancellation
            <?php else :?>
            <a href="<?=Url::to(['order/cancel', 'id' => $order->id]);?>" class='cancel-order-button'><img class="icon-sm btn-delete" src="/images/icon/trash-can.svg"></a>
            <?php endif;?>
            <?php endif;?>
          </td>
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

<!-- Modal order view-->
<div class="modal fade modal-kg" id="paymentGame" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog  modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      
    </div>
  </div>
</div>
<!-- end modal order view -->

<!-- Modal -->
<div class="modal fade" id="img-modal" tabindex="-1" role="dialog" aria-labelledby="img-modal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
    </div>
  </div>
</div>
<!-- end modal image slider -->

<!-- Modal survey-->
<div class="modal fade" id="modalSurvey" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      
    </div>
  </div>
</div>
<!-- End modal survey-->

<?php
$viewUrl = Url::current();
$script = <<< JS
var complain = new Complains({id: '#detailOrder'});
var triggerSendComplainButton = function () {
  var form = $(this).closest('form')[0]; 
  $.ajax({
    url: $(form).attr('action'),
    type: $(form).attr('method'),
    dataType : 'json',
    data: $(form).serialize(),
    success: function (result, textStatus, jqXHR) {
      if (result.status == false) {
          toastr.error(result.errors);
      } else {
          form.reset();
          complain.showList();
          complain.scrollDown();
          checkNewMessage();
      }
    },
  });
}
$('#detailOrder').on('show.bs.modal', function (e) {
    $(this).find('.modal-content').load(e.relatedTarget.href);
    setTimeout(() => {  
      if ($(this).find('#stars li.hover').length) {
        $(this).find('#stars li>a').replaceWith('<span class="icon-star"></span>');
      } else {
        $(this).find('#stars li').on('mouseover', function () {
          var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on

          // Now highlight all the stars that's not after the current hovered star
          $(this).parent().children('li.star').each(function (e) {
            if (e < onStar) {
              $(this).addClass('hover');
            } else {
              $(this).removeClass('hover');
            }
          });
        }).on('mouseout', function () {
          $(this).parent().children('li.star').each(function (e) {
            $(this).removeClass('hover');
          });
        });
      }

      // complain
      complain.init({
        id: '#detailOrder',
        container: '.complain-list',
        url: $(this).find('.complain-list').attr('data-url')
      });
      $('#detailOrder').on('click', '#send-complain-button', triggerSendComplainButton);
    }, 2000);
}).on('hide.bs.modal', function(e) {
  console.log('hide.bs.modal');
  clearInterval(complain.interval);
  $('#detailOrder').off('click', '#send-complain-button', triggerSendComplainButton);
  history.pushState({}, '', '$viewUrl');
});
$('#paymentGame').on('show.bs.modal', function (e) {
  $(this).find('.modal-content').load(e.relatedTarget.href);
}).on('hide.bs.modal', function(e) {
  history.pushState({}, '', '$viewUrl');
});

$('#img-modal').on('show.bs.modal', function (e) {
  var linkOrderId = $(e.relatedTarget).data('order');
  var contentOrderId = $(this).find('.modal-slider').data('order');
  if (linkOrderId == contentOrderId) return;
  $(this).find('.modal-content').load(e.relatedTarget.href);
  setTimeout(() => {  
    $('#img-modal').find('.modal-slider').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      infinite: true,
    });
  }, 5000);
});

$('#modalSurvey').on('show.bs.modal', function (e) {
    $(this).find('.modal-content').load(e.relatedTarget.href);
});

// Update order
$('#paymentGame').on('click', '#update-payment-button', function(e) {
  e.preventDefault();
  var baseForm = $(this).closest('form');
  var url = baseForm.attr('action');
  var form = new FormData();
  baseForm.find('input').each(function( index ) {
    var elementName = $(this).attr('name');
    if ($(this).attr('type') == 'file') {
      $.each(this.files, function( index, value ) {
        form.append(elementName, value);
      });
    } else {
      form.append(elementName, $(this).val());
    }
  });
  $.ajax({
      url: url,
      type: "POST",
      processData: false, // important
      contentType: false, // important
      dataType : 'json',
      data: form,
      success: function (result, textStatus, jqXHR) {
        if (!result.status) {
          toastr.error(result.errors);
        } else {
          setTimeout(() => {  
              location.reload();
          }, 2000);
          toastr.success(result.message); 
        }
      },
  });
});

// Rating order
$('#modalSurvey').on('click', '#rating-order-button', function(e) {
  e.preventDefault();
  var form = $(this).closest('form');
  $.ajax({
      url: form.attr('action'),
      type: form.attr('method'),
      dataType : 'json',
      data: form.serialize(),
      success: function (result, textStatus, jqXHR) {
        if (result.status == false) {
            toastr.error(result.errors);
            return false;
        } else {
            toastr.success('Success');
            $('#modalSurvey').modal('hide');
            $('#detailOrder').find('#stars li').off('mouseover').off('mouseout').removeClass('hover');
            $('#detailOrder').find('#stars li').filter(function() {
              return  $(this).data("value") <= result.rating;
            }).addClass('hover');
            $('#detailOrder').find('#stars li>a').replaceWith('<span class="icon-star"></span>');
        }
      },
  });
  return false;
});


// Show order
var hash = window.location.hash.substr(1).trim();
if (hash) {
  console.log('hash', hash);
  var id = '#'+hash;
  if (!$(id).length) {
    var detail = '/order/detail.html?id=' + hash;
    var newLink = $('<a href="'+detail+'" data-toggle="modal" data-target="#detailOrder" style="display:none" id="'+hash+'"></a>');
    $('body').append(newLink);
  }
  $(id).click();
}

// Check new message
var checkNewMessage = function() {
  $.ajax({
      url: '$checkNewMessageUrl',
      type: "GET",
      dataType: "json",
      success: function(data) {
          if (data.status) {
            var mapping = data.mapping;
            Object.keys(mapping).map(k => {
              var img = $('#newMessageAlert' + k);
              if (mapping[k]) { // has new message
                img.removeClass('d-none');
              } else { // no new message
                img.addClass('d-none');
              }
            });
          }
          startCheck();
      }
  });
};
var startCheck = function(restart) {
  if (restart && _checkNewMessage){
      clearTimeout(_checkNewMessage);
  }
  _checkNewMessage = setTimeout(function() {
      checkNewMessage();
  }, 60000);
}
startCheck();

// Cancel order 
$('.cancel-order-button').ajax_action({
  method: 'POST',
  confirm: true,
  confirm_text: 'Do you want to send a cancellation request for this order?',
  callback: function(element, data) {
    $(element).replaceWith('Waiting cancellation');
  },
  error: function(errors) {
    toastr.error(errors);
  },
});

// Confirm delivery
$('#detailOrder').on('click', '#confirm-order-button', function(e) {
  e.preventDefault();
  e.stopImmediatePropagation();
  if (!window.confirm('Do you want to confirm delivery for this order?')) {
      return false;
  }
  var element = this;
  $.ajax({
    url: $(this).attr('href'),
    type: 'POST',
    dataType : 'json',
    success: function (result, textStatus, jqXHR) {
      if (result.status == false) {
        toastr.error(result.errors);
        return false;
      } else {
        toastr.success('Confirm successfully');
        $(element).replaceWith('<button type="button" class="btn text-uppercase">comfirm delivery</button>');
      }
    }
  });
});
JS;
$this->registerJs($script);
?>