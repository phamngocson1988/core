<?php $authAuthChoice = \yii\authclient\widgets\AuthChoice::begin([
    'baseAuthUrl' => ['site/auth'],
    'popupMode' => false,
  ]); ?>
  <?php foreach ($authAuthChoice->getClients() as $client): ?>
    <li class="list-inline-item"><?= $authAuthChoice->clientLink($client) ?></li>
  <?php endforeach; ?>
  <?php \yii\authclient\widgets\AuthChoice::end(); ?>