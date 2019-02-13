{use class='yii\widgets\ActiveForm' type='block'}
{use class='common\widgets\ImageInputWidget'}
{use class='common\widgets\RadioListInput'}
<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">{Yii::t('app', 'create_package')}</h4>
    </div>
    {ActiveForm assign='form' options=['id' => 'add-product-form'] action='/product/create'}
    <div class="modal-body">
    {$form->field($model, 'game_id', ['template' => '{input}', 'options' => ['tag' => null]])->hiddenInput()}
      <div class="row">
        <div class="col-md-12">
          {$form->field($model, 'title')}
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          {$form->field($model, 'price')}
        </div>
        <div class="col-md-6">
          {$form->field($model, 'gems')}
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          {$form->field($model, 'image_id', [
            'options' => ['tag' => false, 'class' => 'profile-userpic'],
            'template' => '{input}{hint}{error}'
          ])->widget(ImageInputWidget::className(), [
            'template' => '<div class="profile-userpic">{image}{input}</div><div class="profile-userbuttons list-separated profile-stat">{choose_button}{cancel_button}</div>',
            'imageOptions' => ['class' => 'img-responsive', 'size' => '300x300'],
            'chooseButtonOptions' => ['tag' => 'span', 'options' => ['class' => 'btn btn-circle green btn-sm']],
            'cancelButtonOptions' => ['tag' => 'button', 'options' => ['class' => 'btn btn-circle red btn-sm']]
          ])->label(false)}
        </div>
        <div class="col-md-6">
          {$form->field($model, 'status', [
            'options' => ['class' => 'list-separated profile-stat']
          ])->widget(RadioListInput::className(), [
            'items' => $model->getStatusList(),
            'options' => ['class' => 'mt-radio-list']
          ])}
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">{Yii::t('app', 'cancel')}</button>
      <button type="submit" class="btn btn-primary">{Yii::t('app', 'submit')}</button>
    </div>
    {/ActiveForm}
  </div>
</div>
