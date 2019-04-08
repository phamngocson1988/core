<?php
namespace backend\rbac;

use Yii;
use yii\rbac\Rule;
use backend\forms\FetchOrderForm;
use common\models\Order;

/**
 * Checks if authorID matches user passed via params
 */
class TakenOrderRule extends Rule
{
    public $name = 'taken_order_rule';

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
        if ($order->isVerifyingOrder()) return false;        
        if ($order->handler_id) return false;
        if (Yii::$app->user->can('admin')) return true;
        if (Yii::$app->user->can('handler')) {
            $checkTaken = new FetchOrderForm([
                'handler_id' => Yii::$app->user->id,
                'status' => Order::STATUS_PENDING
            ]);
            $checkTakenCommand = $checkTaken->getCommand();
            return ($checkTakenCommand->count() < 1);
        }
        return false;        
    }
}