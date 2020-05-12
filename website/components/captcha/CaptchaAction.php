<?php
namespace website\components\captcha;

class CaptchaAction extends \yii\captcha\CaptchaAction
{
    public $minLength = 6;
    public $maxLength = 6;
    protected function generateVerifyCode()
    {
        if ($this->minLength > $this->maxLength) {
            $this->maxLength = $this->minLength;
        }
        if ($this->minLength < 3) {
            $this->minLength = 3;
        }
        if ($this->maxLength > 20) {
            $this->maxLength = 20;
        }
        $length = mt_rand($this->minLength, $this->maxLength);

        $letters = '0123456789';
        $code = '';
        for ($i = 0; $i < $length; ++$i) {
            $code .= $letters[mt_rand(0, 9)];
        }

        return $code;
    }
}
