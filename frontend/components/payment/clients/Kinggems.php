<?php
namespace frontend\components\payment\clients;

use Yii;
use yii\base\Model;
use yii\web\BadRequestHttpException;
use yii\base\Exception;
use frontend\components\payment\PaymentGateway;
use yii\helpers\Url;
use frontend\forms\PurchaseGameForm;

class Kinggems extends PaymentGateway
{
    public $identifier = 'kinggems';

    protected function loadData()
    {
        $cart = $this->cart;
        $user = Yii::$app->user->identity;
        $itemList = [];
        foreach ($cart->getItems() as $cartItem) {
            $itemList[] = [
                'id' => $cartItem->getId(),
                'title' => $cartItem->getTitle(),
                'quantity' => $cartItem->getQuantity(),
                'price' => $cartItem->getPrice()
            ];
        }
        return [
            'items' => $itemList,
            'payer' => $user->auth_key,
            'ref_key' => $this->getReferenceId(),
        ];
    }

    protected function sendRequest()
    {
        // Create order
        $user = Yii::$app->user->getIdentity();
        $cart = Yii::$app->cart;
        $form = new PurchaseGameForm([
            'user' => $user,
            'cart' => $cart
        ]);
        $form->on(PurchaseGameForm::EVENT_AFTER_PURCHASE, [ShoppingEventHandler::className(), 'sendNotificationEmail']);
        $form->on(PurchaseGameForm::EVENT_AFTER_PURCHASE, [ShoppingEventHandler::className(), 'applyVoucherForUser']);
        $form->on(PurchaseGameForm::EVENT_AFTER_PURCHASE, [ShoppingEventHandler::className(), 'applyAffiliateProgram']);
        if (!$form->purchase()) {
            return $this->redirect($this->getErrorUrl());
        } else {
            $cart->clear();
        }
        return $this->render('/site/notice', [           
            'title' => 'Place order successfully',
            'content' => 'Congratulation.'
        ]);
    }
    
    protected function verify($response)
    {
        return true;

        // $user = Yii::$app->user->getIdentity();
        // $cart = Yii::$app->cart;
        // $form = new PurchaseGameForm([
        //     'user' => $user,
        //     'cart' => $cart
        // ]);
        // $form->on(PurchaseGameForm::EVENT_AFTER_PURCHASE, [ShoppingEventHandler::className(), 'sendNotificationEmail']);
        // $form->on(PurchaseGameForm::EVENT_AFTER_PURCHASE, [ShoppingEventHandler::className(), 'applyVoucherForUser']);
        // $form->on(PurchaseGameForm::EVENT_AFTER_PURCHASE, [ShoppingEventHandler::className(), 'applyAffiliateProgram']);
        // if (!$form->purchase()) {
        //     print_r($form->getErrorSummary(true));die;
        // } else {
        //     $cart->clear();
        // }
        // return $this->render('/site/notice', [           
        //     'title' => 'Place order successfully',
        //     'content' => 'Congratulation.'
        // ]);
    }

    public function cancelPayment()
    {
        return true;
    }

    public function doSuccess()
    {
        return $this->redirect($this->getSuccessUrl());
    }

    public function doError()
    {
        return $this->redirect($this->getErrorUrl());
    }

}