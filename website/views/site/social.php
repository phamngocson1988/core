<ul class="list-inline text-center">
  <?php $authAuthChoice = \yii\authclient\widgets\AuthChoice::begin([
    'baseAuthUrl' => ['site/auth'],
    'popupMode' => false,
  ]); ?>
  <?php foreach ($authAuthChoice->getClients() as $key => $client): ?>
    <li class="list-inline-item"><?= $authAuthChoice->clientLink($client) ?></li>
  <?php endforeach; ?>
  <?php \yii\authclient\widgets\AuthChoice::end(); ?>
</ul>