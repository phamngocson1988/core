<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * OrderFee model
 */
class PromotionRule extends ActiveRecord
{
    public static $rules = [
        'total_using' => [
            'class' => '\common\models\promotions\TotalUsingRule',
            'label' => 'Total of using',
            'title' => 'Limit the number of using this promotion'
        ],
        'user_using' => [
            'class' => '\common\models\promotions\UserUsingRule',
            'label' => 'Total of using for 1 user',
            'title' => 'Limit the number of using this promotion for 1 user'
        ]
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%promotion_rule}}';
    }

    public function addRule($rule)
    {
        $this->data = serialize($rule);
    }

    public function isValid($user, $game)
    {
        $object = unserialize($this->data);
        $object->promotion_id = $this->promotion_id;
        return $object->validate($user, $game);
    }
}
