<?php
namespace backend\widgets;

use yii\widgets\InputWidget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use Yii;

class YMDInputWidget extends InputWidget
{
    protected $_generatedId;
    public function run()
    {
        $id = $this->getId();
        $name = $this->name;
        $ymdId = $this->getYmdId();
        $this->options['id'] = $ymdId;
        $hidden = Html::activeHiddenInput($this->model, $this->attribute, $this->options);
        $value = Html::getAttributeValue($this->model, $this->attribute);

        $ymd = explode( '-', $value );
        $yearValue = array_shift($ymd);
        $monthValue = array_shift($ymd);
        $dayValue = array_shift($ymd);
        $yearId = $this->getYearControlId();
        $monthId = $this->getMonthControlId();
        $dayId = $this->getDayControlId();
        $year = Html::dropDownList($name . '_year', $yearValue, $this->getYears(), ['prompt' => 'Chọn năm', 'class' => 'form-control', 'style' => 'margin-right: 10px', 'id' => $yearId ]);
        $month = Html::dropDownList($name . '_month', $monthValue, $this->getMonths(), ['prompt' => 'Chọn tháng', 'class' => 'form-control', 'style' => 'margin-right: 10px; margin-left: 10px', 'id' => $monthId ]);
        $day = Html::dropDownList($name . '_day', $dayValue, $this->getDays($yearValue, $monthValue), ['prompt' => 'Chọn ngày', 'class' => 'form-control', 'style' => 'margin-left: 10px', 'id' => $dayId ]);
        echo $year . $month . $day . $hidden;
        $this->registerClientScript();
    }

    protected function getGeneratedId()
    {
        if (!$this->_generatedId) {
            $this->_generatedId = Yii::$app->security->generateRandomString();
        }
        return $this->_generatedId;
    }

    protected function getYearControlId()
    {
        return sprintf('%s_year', $this->getGeneratedId());
    }

    protected function getMonthControlId()
    {
        return sprintf('%s_month', $this->getGeneratedId());
    }
    protected function getDayControlId()
    {
        return sprintf('%s_day', $this->getGeneratedId());
    }

    protected function getYmdId()
    {
        return $this->getGeneratedId();
    }

    protected function getMonths()
    {
        $arr = range(1, 12);
        $arr = array_map(function($input) { 
            return str_pad($input, 2, "0", STR_PAD_LEFT); 
        }, $arr); 
        return array_combine($arr, $arr);
    }

    protected function getYears()
    {
        $currentYear = date('Y');
        $arr = range($currentYear, $currentYear - 100);
        return array_combine($arr, $arr);
    }

    public function getDays($year, $month)
    {
        $long = ['01', '03', '05', '07', '08', '10', '12'];
        $short = ['04', '06', '09', '11'];
        $arr = [];
        if (in_array($month, $long)) $arr = range(1, 31);
        elseif (in_array($month, $short)) $arr = range(1, 30);
        elseif ($month == 2) {
            $arr = (int)$year % 4 === 0 ? range(1, 29) : range(1, 28);
        } else {
            $arr = [];
        }
        $arr = array_map(function($input) { 
            return str_pad($input, 2, "0", STR_PAD_LEFT); 
        }, $arr); 
        return array_combine($arr, $arr);
    }

    protected function registerClientScript()
    {
        $js = [];
        $view = $this->getView();
        $js[] = $this->getScriptCode();
        $view->registerJs(implode("\n", $js));
    }

    protected function getScriptCode()
    {
        $yearId = $this->getYearControlId();
        $monthId = $this->getMonthControlId();
        $dayId = $this->getDayControlId();
        $ymdId = $this->getYmdId();

        $yearRange = $this->getYears();
        $monthRange = $this->getMonths();
        return "
        $('#$yearId, #$monthId').on('change', function() {
            var yearValue = $('#$yearId').val();
            var monthValue = $('#$monthId').val();
            var dayValue = $('#$dayId').val();
            var dayList = getDayList(yearValue, monthValue);
            if (!dayList.length) return;
            renderDayDropDown(dayList, dayValue);
            renderDate();
        });
        $('#$dayId').on('change', function() {
            renderDate();
        });
        function getDayList(year, month) {
            try {
                var long = ['01', '03', '05', '07', '08', '10', '12'];
                var short = ['04', '06', '09', '11'];
                if (long.includes(month)) {
                    return range(1, 31);
                } else if (short.includes(month)) {
                    return range(1, 30);
                } else if (month == 2) {
                    return parseInt(year) % 4 === 0 ? range(1, 29) : range(1, 28);
                } else {
                    return [];
                }
            } catch (e) {
                console.log(e);
                return [];
            }
        }

        function renderDayDropDown(dayList, dayValue) {
            var placeholder = $('#$dayId').find('option').first();
            $('#$dayId').html('').append(placeholder);
            for (let i = 0; i < dayList.length; i++) {
                $('#$dayId').append( '<option value=\"' + dayList[i] + '\">' + dayList[i] + '</option>' );
            }
            if (!dayValue) return;
            if (!dayList.includes(dayValue)) {
                dayValue = dayList[dayList.length - 1];
            }
            $('#$dayId').val(dayValue);
        }
            
        function renderDate() {
            var yearValue = $('#$yearId').val();
            var monthValue = $('#$monthId').val();
            var dayValue = $('#$dayId').val();
            if (yearValue && monthValue && dayValue) {
                $('#$ymdId').val([yearValue, String(monthValue).padStart(2, '0'), String(dayValue).padStart(2, '0')].join('-'));
            } else {
                $('#$ymdId').val('');
            }
        }

        function range(min, max) {
            let range = [...Array(max + 1).keys()];
            range = range.filter(function(x) { 
                return x >= min && x <= max;
            });
            return range.map(x => String(x).padStart(2, '0'));
        }
";
    }
}