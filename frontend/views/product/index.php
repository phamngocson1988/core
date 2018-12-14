<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="row">
    <?php foreach ($products as $product) : ?>
    <div class="col-lg-4">
        <h2><?=Html::a($product->title, Url::to(['product/view', 'id' => $product->id]));?></h2>
        <p><?=$product->excerpt;?></p>
    </div>
    <?php endforeach;?>
</div>