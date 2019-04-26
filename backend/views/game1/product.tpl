{use class='yii\widgets\ActiveForm' type='block'}

<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<h4 class="modal-title">{Yii::t('app', 'create_package')}</h4>
</div>
{ActiveForm assign='newform' options=['id' => 'add-product-form']}
<div class="modal-body">
<div class="row">
    <div class="col-md-12">
    {$newform->field($model, 'title')}
    </div>
</div>
<div class="row">
    <div class="col-md-6">
    {$newform->field($model, 'price')}
    </div>
    <div class="col-md-6">
    {$newform->field($model, 'unit')}
    </div>
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">{Yii::t('app', 'cancel')}</button>
<button type="submit" class="btn btn-primary">{Yii::t('app', 'submit')}</button>
</div>
{/ActiveForm}
{registerJs}
{literal}
var newform = new AjaxFormSubmit({element: '#add-product-form'});
newform.success = function(data, form) {
  $(form)[0].reset();
  $('#new-product-modal').modal('hide');
  $('.product-filter.active').click();
}
newform.error = function(errors) {
  console.log(errors);
}
{/literal}
{/registerJs}