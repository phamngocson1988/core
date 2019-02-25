<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>
<div class="body-content">
    <h2>Cart</h2>

    <hr/>
    <table border="1" width="100%">
        <?php foreach ($items as $item) :?>
        <tr>
            <td><?=$item->getUniqueId();?></td>    
            <td><?=$item->getLabel();?></td>    
            <td><?=$item->getPrice();?></td>    
        </tr>
        <?php endforeach;?>
    </table>
</div>
