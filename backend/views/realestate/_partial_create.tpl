<div class="portlet box blue-hoki edit-product-form" id="edit-product-form-{$editProductForm->id}" data-id="{$editProductForm->id}" data-url="{url route='product/edit' id=$editProductForm->id}">
  {$form->field($editProductForm, 'game_id', ['template' => '{input}', 'options' => ['tag' => null]])->hiddenInput(['value' => $editProductForm->game_id])}
  {$form->field($editProductForm, 'id', ['template' => '{input}', 'options' => ['tag' => null]])->hiddenInput(['value' => $editProductForm->id])}
  <div class="portlet-title">
    <div class="caption">
      <i class="fa fa-gift"></i>{$editProductForm->title}
    </div>
    <div class="tools">
      <a href="" class="collapse" data-original-title="" title=""> </a>
      <a href="{url route='product/delete' id=$editProductForm->id}" class="remove" data-original-title="" title=""> </a>
    </div>
  </div>
  <div class="portlet-body form">
    <div class="form-body">
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <div class="fileinput fileinput-new" data-provides="fileinput">
              <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 150px; height: 150px;">
                <img src="{$editProductForm->getImageUrl('150x150')}" />
              </div>
              <div>
                <span class="help-block"> {Yii::t('app', 'image_size_at_least', ['size' => '940x630'])} </span>
                <span class="btn default btn-file">
                  <span class="fileinput-new product-image"> {Yii::t('app', 'choose_image')} </span>
                  {$form->field($editProductForm, 'image_id', [
                    'template' => '{input}', 
                    'options' => ['tag' => null],
                    'inputOptions' => ['class' => 'product-image_id']
                  ])->hiddenInput()->label(false)}
                </span>
                <a href="javascript:void(0)" onclick="removeMainImage()" class="btn red fileinput-exists" data-dismiss="fileinput"> {Yii::t('app', 'remove')} </a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-9">
          <div class="row">
          {$form->field($editProductForm, 'title', [
            'options' => ['class' => 'form-group col-md-6'],
            'labelOptions' => ['class' => 'col-md-4 control-label'],
            'template' => '{label}<div class="col-md-8">{input}{hint}{error}</div>',
            'inputOptions' => ['class' => 'form-control product-title']
          ])->textInput()}
          {$form->field($editProductForm, 'status', [
            'options' => ['class' => 'form-group col-md-6'],
            'labelOptions' => ['class' => 'col-md-4 control-label'],
            'template' => '{label}<div class="col-md-8">{input}{hint}{error}</div>',
            'inputOptions' => ['class' => 'form-control product-status']
          ])->dropDownList($editProductForm->getStatusList())}
          </div>
          <div class="row">
          {$form->field($editProductForm, 'price', [
            'options' => ['class' => 'form-group col-md-6'],
            'labelOptions' => ['class' => 'col-md-4 control-label'],
            'template' => '{label}<div class="col-md-8">{input}{hint}{error}</div>',                                
            'inputOptions' => ['class' => 'form-control product-price']
          ])->textInput()}
          {$form->field($editProductForm, 'gems', [
            'options' => ['class' => 'form-group col-md-6'],
            'labelOptions' => ['class' => 'col-md-4 control-label'],
            'template' => '{label}<div class="col-md-8">{input}{hint}{error}</div>',
            'inputOptions' => ['class' => 'form-control product-gems']
          ])->textInput()}
          </div>
        </div>
      </div>
    </div>
    <div class="form-actions">
      <div class="row">
        <div class="col-md-offset-3 col-md-9">
          <button type="submit" class="btn green">{Yii::t('app', 'submit')}</button>
          <button type="button" class="btn default">{Yii::t('app', 'cancel')}</button>
        </div>
      </div>
    </div>
  </div>
</div>