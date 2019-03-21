<?php
use yii\widgets\Pjax;
use yii\widgets\LinkPager;
?>
<section class="section section-lg bg-default text-center">
        <!-- section wave-->
  <div class="container">
    <div class="row justify-content-sm-center">
      <div class="col-md-12 col-xl-12">
        <h3>Transactions</h3>
        <?php Pjax::begin();?>
        <table class="table-custom table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>Date</th>
              <th>Amount</th>
              <th>Method</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!$models) :?>
            <tr><td colspan="5">No data found</td></tr>
            <?php endif;?>
            <?php foreach ($models as $model) :?>
            <tr>
              <td>#<?=$model->auth_key;?></td>
              <td><?=$model->created_at;?></td>
              <td>$<?=number_format($model->amount);?></td>
              <td><?=$model->payment_method;?></td>
              <td><?=$model->status;?></td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
        <?=LinkPager::widget([
          'pagination' => $pages,
          'options' => ['class' => 'pagination-custom'],
          'linkContainerOptions' => ['class' => 'page-item'],
          'linkOptions' => ['class' => 'page-link'],
        ]);?>
        <?php Pjax::end();?>
      </div>
    </div>
  </div>
</section>