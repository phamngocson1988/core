<?php
namespace common\components\telecom;
use Yii;

class ParseMessage
{
    public $parsers = [
        [
            'class' => '\common\components\telecom\parser\ParseReceiverPhone',
        ],
        [
            'class' => '\common\components\telecom\parser\ParseReceiverName'
        ]
    ];

    public function parse($record) 
    {
        $rules = [];
        foreach ($this->parsers as $parse) {
            $parser = Yii::createObject($parse);
            $rules[$parser->pattern] = $parser->parse($record);
        }
        return strtr($record->message, $rules);
    }
}