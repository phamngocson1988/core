{use class='yii\widgets\Pjax' type='block'}
{use class='yii\widgets\ActiveForm' type='block'}
{Pjax}
<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">{Yii::t('app', 'edit_package')}</h4>
    </div>
    {ActiveForm assign='form' options=['class' => 'edit-product-form'] action={url route="product/edit" id=$product->id}}
    <div class="modal-body">
      {$form->field($product, 'id', ['template' => '{input}', 'options' => ['tag' => null]])->hiddenInput()}
      {$form->field($product, 'game_id', ['template' => '{input}', 'options' => ['tag' => null]])->hiddenInput()}
      <div class="row">
        <div class="col-md-12">
          {$form->field($product, 'title')}
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          {$form->field($product, 'price')}
        </div>
        <div class="col-md-6">
          {$form->field($product, 'unit')}
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
{/Pjax}