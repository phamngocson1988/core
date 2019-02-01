{use class='yii\widgets\ActiveForm' type='block'}
{use class='common\widgets\ImageInputWidget'}
{use class='common\widgets\RadioListInput'}
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">{Yii::t('app', 'create_package')}</h4>
      </div>
      {ActiveForm assign='form' options=['class' => 'ajax-form-submit']}
      <div class="modal-body" id="create_product">
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
        <button type="submit" class="btn btn-primary add-product-button">{Yii::t('app', 'submit')}</button>
      </div>
      {/ActiveForm}
    </div>
  </div>
</div>
        
