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
            <td>
                <?php
                echo Html::beginForm(['cart/add'], 'post', ['class' => 'ajax-form-submit']);
                echo Html::hiddenInput('id', $option->id);
                echo Html::submitButton('Add to cart', ['class' => 'btn btn-link']);
                echo Html::endForm();
                ?>
            </td>    
        </tr>
        <?php endforeach;?>
    </table>
</div>

<?php
$script = <<< JS
var f = AjaxFormSubmit();
f.success = function(data, form) {
    alert('success');
    console.log(data);
};
JS;
$this->registerJs($script);
?>
