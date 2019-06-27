<?php
namespace backend\rbac;

use Yii;
use yii\rbac\Rule;

/**
 * Checks if authorID matches user passed via params
 */
class DeleteOrderRule extends Rule
{
    public $name = 'delete_order_rule';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        $order = isset($params['order']) ? $params['order'] : null;
        if (!$order) return false;
        if (!$order->isVerifyingOrder()) return false;
        if (Yii::$app->user->can('admin')) return true;
        if (Yii::$app->user->can('orderteam')) return false;
        if (Yii::$app->user->can('saler') && !$order->saler_id) return true;
        if (Yii::$app->user->can('saler') && $order->saler_id == Yii::$app->user->id) return true;
        return false;
    }
}