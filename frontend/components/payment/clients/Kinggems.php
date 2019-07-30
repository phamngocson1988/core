<?php
namespace frontend\components\payment\clients;

use Yii;
use yii\base\Model;
use yii\web\BadRequestHttpException;
use yii\base\Exception;
use frontend\components\payment\PaymentGateway;

class Kinggems extends PaymentGateway
{
    public $identifier = 'kinggems';

    protected function loadData()
    {
        $cart = $this->cart;
        $user = Yii::$app->user->identify;
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
            'secret' => Yii::$app->session->setFlash('kinggems_paygate', Yii::$app->security->generateRandomString())
        ];
    }

    protected function sendRequest()
    {
        $payment = $this->loadData();
        try {
            // create transaction
            $server = Url::to(['cart/kinggems', 'scenario' => 'create'], true);
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $server); 
            curl_setopt($ch, CURLOPT_POST, TRUE); 
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payment);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
            $payload = curl_exec($ch); 
            curl_close($ch);
            $transaction = json_decode($payload, true);
            if ($transaction['status'] == false) {
                die($transaction['message']);
            }

            // approve transaction
            $appoveData = [
                'id' => $transaction['id'], 
                'error_url' => $this->getErrorUrl(),
                'success_url' => $this->getSuccessUrl(),
                'verify_url' => $this->getConfirmUrl()
            ];
            $approveLink = Url::to(array_merge(['cart/kinggems', 'scenario' => 'approve'], $appoveData), true);
            return $this->redirect($approveLink);
        } catch (PayPalConnectionException $ex) {
            throw $ex;
        }
    }
    
    protected function verify($response)
    {
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