<?php
namespace backend\rbac;

use Yii;
use yii\rbac\Rule;

/**
 * Checks if the user can view customer info
 */
class ViewCustomerRule extends Rule
{
    public $name = 'view_customer_rule';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        $user = Yii::$app->user;
        if ($user->can('admin')) return true;
        elseif ($user->can('orderteam_manager')) return true;
        elseif ($user->can('handler')) return false;
        else return true;
    }
}