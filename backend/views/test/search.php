<?php 
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\JsExpression;
?>
<input type="file" name="upload_file" id="file"/>
<button id='button'>Submit</button>
<?php
$script = <<< JS
var form = AjaxUploadFile({trigger_element: '#button', file_element: '#file'});

JS;
$this->registerJs($script);
?>