<table class="table table-striped table-bordered table-hover table-checkable">
  <thead>
    <tr>
      <th>ID</th>
      <th>Khách hàng</th>
      <th>Số gói</th>
      <th>Số tiền</th>
      <th>Ngày tạo</th>
      <th>Trạng thái</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($models as $model) : ?>
    <tr>
      <td><?=$model->id;?></td>
      <td><?=$model->customer_name;?></td>
      <td><?=$model->quantity;?></td>
      <td><?=$model->total_price;?></td>
      <td><?=$model->created_at;?></td>
      <td><?=$model->status;?></td>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>
<pre>
<?php 
print_r($data);
?>
</pre>
