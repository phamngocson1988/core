<?php
use yii\widgets\LinkPager;
?>
<table>
<?php foreach ($models as $model) : ?>
<tr>
    <td><?=$model->email;?></td>
    <td><?=$model->name;?></td>
</tr>
<?php endforeach; ?>
</table>
<?=LinkPager::widget(['pagination' => $pages])?>