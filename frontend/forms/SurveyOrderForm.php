<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;
use frontend\models\Order;
use frontend\components\notifications\OrderNotification;

class SurveyOrderForm extends Model
{
    public $id;
    public $rating;
    public $comment_rating;
    public $other;

    private $_order;

     /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'rating'], 'required'],
            ['id', 'validateOrder'],
            ['rating', 'in', 'range' => [1, 2, 3, 4, 5]],
            [['comment_rating', 'other'], 'trim']
        ];
    }

    public function validateOrder($attribute, $params = [])
    {
        $order = $this->getOrder();
        if (!$order) {
            $this->addError($attribute, 'Order is not exist');
        } elseif ($order->customer_id != Yii::$app->user->id) {
            $this->addError($attribute, 'Order is not exist');
        }

    }

    public function survey()
    {
        $order = $this->getOrder();
        $order->rating = $this->rating;
        $commentRating = (array)$this->comment_rating;
        $commentRating[] = $this->other;
        $commentRating = array_filter($commentRating);
        $order->comment_rating = implode(', ', $commentRating);
        return $order->save();
    }


    protected function getOrder()
    {
        if (!$this->_order) {
            $this->_order = Order::findOne($this->id);
        }
        return $this->_order;
    }

    public function fetchCommentRating()
    {
        return [
            'Prices' => 'Prices',
            'Payment' => 'Payment',
            'Delivery Speed' => 'Delivery Speed',
            'Topup Method' => 'Topup Method',
            'Customer Service' => 'Customer Service',
            'Website Experience' => 'Website Experience',
            'Order Placing' => 'Order Placing',
        ];
    }
}

