{use class='yii\widgets\ActiveForm' type='block'}
{ActiveForm options=['class' => 'form-horizontal form-row-seperated', 'enctype'=>'multipart/form-data']  action='test/ajax-upload' method='post'}
  <input type="file" name="imageFiles">
  <button type="submit">Upload</button>
{/ActiveForm}