<?php
use yii\helpers\Html;
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
  <h4 class="modal-title">Chọn một câu trả lời để phản hồi đến khách hàng</h4>
</div>
<div class="modal-body" style="height: 500px; position: relative; overflow: auto; display: block;"> 
  <table class="table">
    <thead>
      <tr>
        <th scope="col" width="5%">#</th>
        <th scope="col" width="90%">Nội dung</th>
        <th scope="col" width="5%">Chọn</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($template_list as $template_item) :?>
      <tr>
        <td><?=$template_item->id;?></td>
        <td><?=$template_item->content;?></td>
        <td>
          <?= Html::beginForm(['order/complain', 'id' => $id], 'POST', ['class' => 'complain-form']); ?>
            <?= Html::hiddenInput('content', $template_item->content); ?>
            <button type="submit" class="btn btn-default" data-toggle="modal"><i class="fa fa-plus"></i> Gửi</button>
          <?= Html::endForm(); ?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<div class="modal-footer">
  <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
</div>
