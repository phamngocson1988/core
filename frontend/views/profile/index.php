<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
$this->title = 'User Profile';
?>
<main>
  <section class="section-profile-user mt-3 mt-md-5">
    <div class="container">
      <div class="widget-box p-3 p-md-5">
        <div class="max-container">
          <?php $form = ActiveForm::begin(); ?>
            <div class="border-bottom pb-3 mb-4">
              <h1 class="sec-title text-center text-uppercase mb-1">COMPLETE YOUR PROFILE</h1>
              <p class="text-center text-uppercase">Set your profile picture</p>
              <div class="heading-image mb-4"><img class="object-fit" src="/img/common/avatar_img_01.png" alt="image"><a class="edit-camera fas fa-camera trans" href="#"></a></div>
              <div class="row pl-md-5 pr-md-5">
                <div class="col-sm-6">
                  <?= $form->field($model, 'firstname', [
                    'options' => ['class' => 'mb-3'],
                    'labelOptions' => ['class' => 'mb-2 text-uppercase'],
                    'inputOptions' => ['class' => 'form-control btn-block']
                  ])->textInput(['placeholder' => 'First name']);?>
                </div>


                <div class="col-sm-6">
                  <?= $form->field($model, 'lastname', [
                    'options' => ['class' => 'mb-3'],
                    'labelOptions' => ['class' => 'mb-2 text-uppercase'],
                    'inputOptions' => ['class' => 'form-control btn-block']
                  ])->textInput(['placeholder' => 'Last name']);?>
                </div>
                <div class="col-sm-6">
                  <?= $form->field($model, 'country', [
                    'options' => ['class' => 'mb-3'],
                    'labelOptions' => ['class' => 'mb-2 text-uppercase'],
                    'inputOptions' => ['class' => 'form-control btn-block']
                  ])->dropdownList($model->fetchCountry(), ['prompt' => Yii::t('app', 'select_country')]);?>
                </div>
                <div class="col-sm-6">
                  <?= $form->field($model, 'gender', [
                    'options' => ['class' => 'mb-3'],
                    'labelOptions' => ['class' => 'mb-2 text-uppercase'],
                    'inputOptions' => ['class' => 'form-control btn-block']
                  ])->dropdownList($model->fetchGender(), ['prompt' => Yii::t('app', 'select_gender')]);?>
                </div>
              </div>
            </div>
            <?php if ($operators) : ?>
            <div class="mb-3 mb-md-5">
              <h1 class="sec-title text-center text-uppercase mb-1">SUGGESTION FOR YOU</h1>
              <p class="text-center">add these operators to your favorites</p>
              <ul class="list-add">
                <?php foreach ($operators as $operator) : ?>
                  <li>
                    <div class="heading-image custom-avatar"><img class="object-fit" src="<?=$operator->getImageUrl('180x180');?>" alt="image"></div>
                    <p class="add-desc"><?=$operator->name;?></p><a class="btn btn-info add-favorite-action" href="<?=Url::to(['operator/add-favorite', 'id' => $operator->id]);?>"><?=Yii::t('app', 'add');?></a>
                  </li>
                <?php endforeach;?>
              </ul>
            </div>
            <?php endif;?>
            <div class="text-center">
              <button class="btn btn-primary text-uppercase pl-4 pr-4" type="submit">COMPLETE YOUR PROFILE</button>
            </div>
          <?php ActiveForm::end();?>
        </div>
      </div>
    </div>
  </section>
</main>
<?php
$script = <<< JS
$(".add-favorite-action").ajax_action({
  confirm: false,
  callback: function(eletement, data) {
    toastr.success(data.message);
    setTimeout(function(){
      location.reload();
    },1000);
  },
  error: function(errors) {
      toastr.error(errors);
  },
});
JS;
$this->registerJs($script);
?>