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
    public $total;

    protected $_all_users;

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['users', 'total'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'users' => 'Khách hàng',
            'total' => 'Số lần sử dụng',
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
            case 'total':
                return $form->field($this, $attr, $options)->textInput();
            default:
                return '';
        }
    }

    public function isValid($params)
    {
        $userId = ArrayHelper::getValue($params, 'user_id');
        if (!$userId); return false;
        if (!in_array($userId, $this->users)) return false;
        if (!$this->total) return true;
        $command = PromotionApply::find()->where(['promotion_id' => $this->promotion_id, 'user_id' => $userId]);
        return $command->count() < $this->total;
    }

    protected function loadAllUsers()
    {
        if (!$this->_all_users) {
            $this->_all_users = User::find()->select(['id', 'email'])->where(['IN', 'status', [User::STATUS_ACTIVE, User::STATUS_INACTIVE]])->all();
        }
        return $this->_all_users;
    }
}