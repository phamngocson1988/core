{use class='yii\widgets\ActiveForm' type='block'}

{use class='dosamigos\typeahead\Bloodhound'}
{use class='dosamigos\typeahead\TypeAhead'}
{use class='yii\helpers\Url'}
{use class='yii\web\JsExpression'}
<div class="portlet box red">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gift"></i>Repeating Forms </div>
        <div class="tools">
            <a href="javascript:;" class="collapse"> </a>
            <a href="#portlet-config" data-toggle="modal" class="config"> </a>
            <a href="javascript:;" class="reload"> </a>
            <a href="javascript:;" class="remove"> </a>
        </div>
    </div>
    <div class="portlet-body form">
        <div class="form-body">
            <div class="form-group">
                {ActiveForm assign='form' options=['class' => 'form-horizontal form-row-seperated'] id='create-form'}
                    <?php
    $engine = new Bloodhound([
        'name' => 'countriesEngine',
        'clientOptions' => [
            'datumTokenizer' => new JsExpression("Bloodhound.tokenizers.obj.whitespace('name')"),
            'queryTokenizer' => new JsExpression("Bloodhound.tokenizers.whitespace"),
            'remote' => [
                'url' => Url::to(['country/autocomplete', 'query'=>'QRY']),
                'wildcard' => 'QRY'
            ]
        ]
    ]);
?>
<?= $form->field($model, 'country')->widget(
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
);?>
                {/ActiveForm}
            </div>
        </div>
    </div>
</div>