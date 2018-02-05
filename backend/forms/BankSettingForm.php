<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

class BankSettingForm extends Model
{
    public $bankName1;
    public $bankName2;
    public $bankName3;
    public $bankName4;

    public $bankHolder1;
    public $bankHolder2;
    public $bankHolder3;
    public $bankHolder4;

    public $bankNumber1;
    public $bankNumber2;
    public $bankNumber3;
    public $bankNumber4;

    public $bankBranch1;
    public $bankBranch2;
    public $bankBranch3;
    public $bankBranch4;

    public function rules()
    {
        return [
            [['bankName1', 'bankHolder1', 'bankNumber1', 'bankBranch1'], 'trim'],
            [['bankName2', 'bankHolder2', 'bankNumber2', 'bankBranch2'], 'trim'],
            [['bankName3', 'bankHolder3', 'bankNumber3', 'bankBranch3'], 'trim'],
            [['bankName4', 'bankHolder4', 'bankNumber4', 'bankBranch4'], 'trim'],
        ];
    }

}