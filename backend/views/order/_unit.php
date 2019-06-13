<div class="table-responsive">
  <table class="table table-hover table-bordered table-striped">
    <thead>
      <tr>
        <th> Tên game </th>
        <th> Số lượng nạp </th>
        <th> Số gói </th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><?=$order->game_title;?></td>
        <td><?=$order->total_unit;?></td>
        <td><?=$order->quantity;?></td>
      </tr>
    </tbody>
  </table>
</div>