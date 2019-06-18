<?php
namespace common\components\telecom\parser;
use common\models\Contact;

class ParseReceiverName
{
    public $pattern = '%%%NAME%%%';

    public function parse($record) 
    {
        $contact = Contact::find()->where(['phone' => $record->phone])->one();
        return ($contact) ? $contact->name : $record->phone;
    }
}