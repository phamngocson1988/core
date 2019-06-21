<?php
namespace frontend\components\payment\events;

use yii\base\Component;
use yii\base\Event;

class BeforeRequestEvent extends Event
{
    public $reference_id;
}
