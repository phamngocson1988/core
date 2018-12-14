<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>
<div class="body-content">
    <h2><?=$product->title;?></h2>
    <?=$product->content;?>

    <hr/>
    <h3>Gói sản phẩm</h3>
    <table border="1" width="100%">
        <?php foreach ($product->options as $option) :?>
        <tr>
            <td><?=$option->title;?></td>    
            <td><?=$option->gems;?></td>    
            <td><?=$option->price;?></td>    
            <td><?=Html::a('Add to cart', Url::to(['product/add', 'id' => $option->id]), ['class' => 'btn btn-success']);?>
            </td>    
        </tr>
        <?php endforeach;?>
    </table>
</div>
