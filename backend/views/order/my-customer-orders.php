<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">Danh sách đơn hàng</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th style="width: 20%;"> Mã đơn hàng </th>
              <th style="width: 20%;"> Ngày tạo </th>
              <th style="width: 35%;"> Tên game </th>
              <th style="width: 20%;"> Thành tiền </th>
            </tr>
          </thead>
          <tbody>
              <?php foreach ($models as $model) :?>
              <tr>
                <td style="vertical-align: middle;">Order #<?=$model->id;?></td>
                <td style="vertical-align: middle;"><?=$model->created_at;?></td>
                <td style="vertical-align: middle;">
                <?php 
                $items = $model->items;
                $item = reset($items);
                echo $item->item_title;
                ?>
                </td>
                <td style="vertical-align: middle;"><?=$model->total_price;?></td>
              </tr>
              <?php endforeach;?>
          </tbody>
        </table>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn default" data-dismiss="modal">Close</button>
</div>