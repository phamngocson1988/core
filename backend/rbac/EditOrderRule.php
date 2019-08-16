<?php
namespace backend\rbac;

use Yii;
use yii\rbac\Rule;

/**
 * Checks if authorID matches user passed via params
 */
class EditOrderRule extends Rule
{
    public $name = 'edit_order_rule';

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
        if ($order->isDeletedOrder()) return false;
        if (Yii::$app->user->can('admin')) return true;
        if ($order->isVerifyingOrder()) {
            // return $order->saler_id == Yii::$app->user->id;
            return Yii::$app->user->can('accounting');
        } else {
            return $order->orderteam_id == Yii::$app->user->id;
        }
        return false;
    }
}