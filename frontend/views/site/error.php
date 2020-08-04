<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\HttpException;
$this->title = $name;
$code = $exception instanceof HttpException ? $exception->statusCode : $exception->getCode();
?>
<main>
  <section class="section-notfound">
    <div class="container">
      <h1 class="notfound-title"><?= $code ?></h1>
      <h2 class="notfound-ttl"><?= Html::encode($this->title) ?></h2>
      <div class="notfound-caption"><?= nl2br(Html::encode($message)) ?></div>
      <a class="btn btn-primary trans" href="/">HOME</a>
    </div>
  </section>
</main>