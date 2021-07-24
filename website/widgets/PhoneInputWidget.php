<?php
namespace website\widgets;

use yii\widgets\InputWidget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use Yii;

class PhoneInputWidget extends InputWidget
{
    public $country_code_attribute = 'country_code';
    public $country_code_id = 'country_code';

    public function run()
    {
        $phone = Html::activeTextInput($this->model, $this->attribute, $this->options);
        $code = Html::activeHiddenInput($this->model, $this->country_code_attribute, ['id' => $this->country_code_id]);
        echo  $phone . $code;
        $this->registerClientScript();
    }

    protected function getScriptCode()
    {
        $id = $this->options['id'];
        $codeId = $this->country_code_id;
        $model = $this->model;
        $attribute = $this->country_code_attribute;
        $countryCode = $attribute && isset($model->$attribute) ? $model->$attribute : '';
        return "
        var input = document.querySelector('#$id');
        var iti = intlTelInput(input);
        iti.setCountry('$countryCode');
        input.addEventListener('countrychange', function() {
            var countryData = iti.getSelectedCountryData();
            let { iso2, dialCode = '' } = countryData || {} ;
            dialCode = '+' + dialCode;
            $('#$codeId').val(iso2);
            let phone = $('#$id').val();
            if (phone.indexOf(dialCode) === 0) {
                phone = phone.substring(dialCode.length);
                $('#$id').val(dialCode + phone);
            } else {
                $('#$id').val(dialCode);
            }
        });
";
    }

    protected function registerClientScript()
    {
        $js = [];
        $view = $this->getView();
        $js[] = $this->getScriptCode();
        $view->registerJs(implode("\n", $js));

        $css =".iti.iti--allow-dropdown {width: 100%}";
        $view->registerCss($css);
    }


}