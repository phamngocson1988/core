<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<?php $form = ActiveForm::begin(['action' => ['reseller/purchase', 'id' => $id]]); ?>
<table>
<tr>
    <td>No</td>
    <td>Quantity</td>
    <td>Coin</td>
</tr>
<?php foreach ($valid_records as $no => $record) :?>
<tr>
    <td>
        <?=$record->no;?>
        <?=Html::hiddenInput("import[$no][quantity]", $record->quantity);?>
        <?=Html::hiddenInput("import[$no][username]", $record->username);?>
        <?=Html::hiddenInput("import[$no][password]", $record->password);?>
        <?=Html::hiddenInput("import[$no][character_name]", $record->character_name);?>
        <?=Html::hiddenInput("import[$no][recover_code]", $record->recover_code);?>
        <?=Html::hiddenInput("import[$no][server]", $record->server);?>
        <?=Html::hiddenInput("import[$no][note]", $record->note);?>
        <?=Html::hiddenInput("import[$no][login_method]", $record->login_method);?>
        <?=Html::hiddenInput("import[$no][platform]", $record->platform);?>
    </td>
    <td><?=$record->quantity;?></td>
    <td><?=$record->getTotalPrice();?></td>
</tr>
<?php endforeach; ?>
</table>
<?=Html::submitButton('Submit');?>
<?php ActiveForm::end();?>
<table>
<tr>
    <td>No</td>
    <td>Error</td>
</tr>
<?php foreach ($invalid_records as $record) :?>
<tr>
    <td><?=$record->no;?>
    </td>
    <td><?php 
    $errors = $record->getErrorSummary(true);
    echo $errors[0];
    ?></td>
</tr>
<?php endforeach; ?>
</table>