<?php
namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Promotion model
 */
class Promotion extends \common\models\Promotion
{
    public function canApplyGame($gameId)
    {
        if (!$this->isValid()) return false;
        if (!$this->isEnable()) return false;
        if ($this->total_using) {
            $command = Order::find();
            $command->joinWith('promotions');
            $command->where(["order_fee.reference" => $this->id]);
            if ((int)$promotion->total_using <= $command->count()) return fasle;
        }
        
        $games = $this->promotionGames;
        // Apply for all games
        if (empty($games)) return true;
        // Apply for current game
        else {
            $gameIds = ArrayHelper::getColumn($games, 'game_id');
            return in_array($gameId, $gameIds);
        }
    }

    public function canApplyUser($userId)
    {
        $users = $this->promotionUsers;
        // Apply for all users
        if (empty($users)) return true;
        // Apply for current user
        else {
            $userIds = ArrayHelper::getColumn($users, 'user_id');
            return in_array($userId, $userIds);
        }
    }

    


    public function checkUserUsing($attribute, $params)
    {
        if (!$this->user_id) return;
        $promotion = $this->getPromotion();
        if (!$promotion) return;
        if (!$promotion->user_using) return;
        $command = Order::find();
        $command->joinWith('promotions');
        $command->where(["order_fee.reference" => $promotion->id]);
        $command->andWhere(["order.customer_id" => $this->user_id]);
        if ((int)$promotion->user_using <= $command->count()) {
            $this->addError($attribute, 'This voucher code has applied before');
        }
    }

    public function checkTotalUsing($attribute, $params)
    {
        $promotion = $this->getPromotion();
        if (!$promotion) return;
        if (!$promotion->total_using) return;
        $command = Order::find();
        $command->joinWith('promotions');
        $command->where(["order_fee.reference" => $promotion->id]);
        if ((int)$promotion->total_using <= $command->count()) {
            $this->addError($attribute, 'This voucher code has been used by others');
        }
    }
}