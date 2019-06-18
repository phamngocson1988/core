<?php
namespace common\components\telecom\parser;

class ParseReceiverPhone
{
    public $pattern = '%%%PHONE%%%';

    public function parse($record) 
    {
        return $record->phone;
    }
}