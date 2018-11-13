<?php 
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use unclead\multipleinput\MultipleInput;
?>
<?php
    $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal'],
    ]);
?>
<?php 
//$form->field($model, 'packages')->widget(MultipleInput::className(), [
//         'allowEmptyList'    => true,
//         'enableGuessTitle'  => true,
//         'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header
//     ])
//     ->label(false);
echo $form->field($model, 'packages')->widget(MultipleInput::className(), [
    'max' => 4,
    'columns' => [
        [
            'name'  => 'user_id',
            'type'  => 'dropDownList',
            'title' => 'User',
            'defaultValue' => 1,
            'items' => [
                1 => 'User 1',
                2 => 'User 2'
            ]
        ],
        [
            'name'  => 'priority',
            'title' => 'Priority',
            'enableError' => true,
            'options' => [
                'class' => 'input-priority'
            ]
        ]
    ]
 ]);
ActiveForm::end()
?>