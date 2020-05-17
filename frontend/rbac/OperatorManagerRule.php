<?php
namespace frontend\rbac;

use yii\rbac\Rule;
use frontend\models\User;

class OperatorManagerRule extends Rule
{
    public $name = 'isOperatorManager';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($userId, $item, $params)
    {
        if (!isset($params['operator'])) return false;
        $operator = $params['operator'];
        return User::find()->where(['id' => $userId])->andWhere(['operator_id' => $operator->id])->exists();
    }
}