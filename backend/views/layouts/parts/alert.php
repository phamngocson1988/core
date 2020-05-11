<?php if (Yii::$app->session->hasFlash('error')) : ?>
<div class="alert alert-danger" role="alert">
  <ul>
  <?php foreach (Yii::$app->session->getFlash('error') as $errors) : ?>
      <?php foreach ($errors as $error) : ?>
          <li><?=$error;?></li>
      <?php endforeach;?>
  <?php endforeach;?>
  </ul>
</div>
<?php endif;?>
<?php if (Yii::$app->session->hasFlash('success')) : ?>
  <div class="alert alert-success" role="alert">
      <?=Yii::$app->session->getFlash('success');?>
  </div>
<?php endif;?>