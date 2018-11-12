<?php 
use yii\widgets\ActiveForm;
use dosamigos\typeahead\Bloodhound;
use dosamigos\typeahead\TypeAhead;
use yii\helpers\Url;
use yii\web\JsExpression;
?>
<?php
    $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal'],
    ]);

    $engine = new Bloodhound([
        'name' => 'countriesEngine',
        'clientOptions' => [
            'datumTokenizer' => new JsExpression("Bloodhound.tokenizers.obj.whitespace('name')"),
            'queryTokenizer' => new JsExpression("Bloodhound.tokenizers.whitespace"),
            'remote' => [
                'url' => Url::to(['test/autocomplete', 'query'=>'QRY']),
                'wildcard' => 'QRY'
            ]
        ]
    ]);
?>
<?= $form->field($model, 'title')->widget(
    TypeAhead::className(),
    [
        'options' => ['class' => 'form-control'],
        'engines' => [ $engine ],
        'clientOptions' => [
            'highlight' => true,
            'minLength' => 3
        ],
        'dataSets' => [
            [
                'name' => 'countries',
                'displayKey' => 'value',
                'source' => $engine->getAdapterScript()
            ]
        ]
    ]
);
ActiveForm::end()
?>