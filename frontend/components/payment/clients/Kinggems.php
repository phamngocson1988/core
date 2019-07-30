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
        $totalPrice = $cart->getTotalPrice();

        $itemList = [];
        foreach ($cart->getItems() as $cartItem) {
            $ppItem = new Item();
            $ppItem->setName($cartItem->getTitle())
            ->setCurrency($currency)
            ->setQuantity($cartItem->getQuantity())
            ->setSku($cartItem->getId())
            ->setPrice($cartItem->getPrice());
            $itemList[] = $ppItem;
        }

        // For discount
        if ($cart->hasDiscount()) {
            $discount = $cart->getDiscount();
            $discountItem = new Item();
            $discountItem->setName($discount->getTitle())
            ->setCurrency($currency)
            ->setQuantity(1)
            ->setSku($discount->getId())
            ->setPrice(($cart->getTotalDiscount()) * (-1));
            $itemList[] = $discountItem;
        }

        $ppitemList = new ItemList();
        $ppitemList->setItems($itemList);

        $details = new Details();
        $details->setShipping(0)
            ->setTax(0)
            ->setSubtotal($totalPrice);
        // ### Amount
        $amount = new Amount();
        $amount->setCurrency($currency)
            ->setTotal($totalPrice)
            ->setDetails($details);

        $transaction = new PaypalTransaction();
        $transaction->setAmount($amount)
            ->setItemList($ppitemList)
            ->setDescription($cart->getTitle())
            ->setInvoiceNumber($this->getReferenceId());
            // ->setInvoiceNumber(uniqid());

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($this->getConfirmUrl())
            ->setCancelUrl($this->getCancelUrl());

        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));
        return $payment;
    }

    protected function sendRequest()
    {
        $payment = $this->loadData();
        try {
            $payment->create($apiContext);
            if (self::PAYMENT_STATE_CREATED == strtolower($payment->state)) {// order was created
                $link = $payment->getApprovalLink();
                $query = parse_url($link, PHP_URL_QUERY);
                parse_str($query, $params);
                return $this->redirect($link);
            }  
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