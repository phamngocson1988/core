<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

class TermsConditionForm extends Model
{
    public $member;
    public $risk;
    public $affiliate;
    public $no_refund;
    public $promotion;

    public function rules()
    {
        return [
            [['member', 'risk', 'affiliate', 'no_refund', 'promotion'], 'trim'],
        ];
    }
}