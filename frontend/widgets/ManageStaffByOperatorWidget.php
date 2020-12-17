<?php
namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use frontend\models\Operator;
use frontend\models\OperatorStaff;

class ManageStaffByOperatorWidget extends Widget
{
    public $operator_id;
    public $operator;

    public function run()
    {
        if (!$this->operator_id) return '';

        $operator = $this->getOperator();

        $data = OperatorStaff::find()->where(['operator_id' => $this->operator_id])
        ->select(["role", "COUNT(role) as count"])
        ->groupBy(["role"])
        ->asArray()
        ->all();
        $count = ArrayHelper::map($data, 'role', 'count');
        $countAdmin = ArrayHelper::getValue($count, OperatorStaff::ROLE_ADMIN, 0);
        $countSubAdmin = ArrayHelper::getValue($count, OperatorStaff::ROLE_SUBADMIN, 0);
        $countModerator = ArrayHelper::getValue($count, OperatorStaff::ROLE_MODERATOR, 0);
        return $this->render('manage_staff_by_operator', [
            'operator' => $operator,
            'countAdmin' => $countAdmin,
            'countSubAdmin' => $countSubAdmin,
            'countModerator' => $countModerator,
        ]);
    }

    public function getOperator()
    {
        if (!$this->operator) {
            $this->operator = Operator::findOne($this->operator_id);
        }
        return $this->operator;
    }
}