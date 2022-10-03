<?php
namespace common\models\promotions;

use Yii;
use common\models\PaymentTransaction;
use common\models\PromotionApply;
use common\models\User;
use dosamigos\datepicker\DatePicker;

/**
 * SpecifiedGamesRule model
 */
class NewMemberRule extends PromotionRuleAbstract implements PromotionRuleInterface
{
    public $object = self::EFFECT_USER;
    public $user_created_at;

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['user_created_at'],
        ];
    }

    public function rules()
    {
        return [
            ['user_created_at', 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_created_at' => 'Ngày đăng ký từ',
        ];
    }

    public function render($form, $attr, $options = [])
    {
        if (!$this->isSafeAttribute($attr)) return '';
        return $form->field($this, $attr, $options)->widget(DatePicker::className(), [
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
            ],
        ]);
    }

    public function isValid($userId)
    {
        $user = User::findOne($userId);
        if (!$user) return false;
        return strtotime($user->created_at) > strtotime($this->user_created_at . ' 00:00:00');
    }
}