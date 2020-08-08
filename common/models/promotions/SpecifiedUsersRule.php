<?php
namespace common\models\promotions;

use Yii;
use yii\helpers\ArrayHelper;
use common\models\PromotionApply;
use common\models\User;

/**
 * SpecifiedUsersRule model
 */
class SpecifiedUsersRule extends PromotionRuleAbstract implements PromotionRuleInterface
{
    public $users;

    public $object = self::EFFECT_USER;

    protected $_all_users;

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['users'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'users' => 'Khách hàng',
        ];
    }

    public function render($form, $attr, $options = [])
    {
        if (!$this->isSafeAttribute($attr)) return '';
        

        switch ($attr) {
            case 'users':
                $allUsers = $this->loadAllUsers();
                return $form->field($this, $attr, $options)->widget(\kartik\select2\Select2::classname(), [
                    'data' => ArrayHelper::map($allUsers, 'id', 'email'),
                    'options' => ['class' => 'form-control', 'multiple' => 'true'],
                ]);
                break;
            default:
                return '';
        }
    }

    public function isValid($userId)
    {
        if (!$userId) return false;
        if (!in_array($userId, $this->users)) return false;
        return true;
    }

    protected function loadAllUsers()
    {
        if (!$this->_all_users) {
            $this->_all_users = User::find()->select(['id', 'email'])->where(['IN', 'status', [User::STATUS_ACTIVE, User::STATUS_INACTIVE]])->all();
        }
        return $this->_all_users;
    }
}