<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\log\Logger;
use common\models\Order;
use izyue\alipay\AlipayNotify;
use izyue\alipay\AlipaySubmit;


class PayController extends Controller
{
    public $layout = 'cart';
    public $enableCsrfValidation = false;


    public function actionSubmit()
    {
        Yii::$app->session['step'] = 3;

        $sn = date('YmdHis');
        if (!$sn)
            return false;

        $subject = $body = '';
        $count = 1;
        $firstProduct = 1;
        $subject = "First product";
        if ($count > 1) {
            $subject .= '等' . $count . '件商品';
        }

        $body .= "SPA" . ' | ';


        header("Content-type:text/html;charset=utf-8");
        $alipay_config['partner'] = Yii::$app->params['alipayPartner'];
        $alipay_config['seller_email'] = Yii::$app->params['alipaySellerEmail'];
        $alipay_config['key'] = Yii::$app->params['alipayKey'];
        $alipay_config['sign_type']    = strtoupper('MD5');
        $alipay_config['input_charset']= strtolower('utf-8');
        $alipay_config['cacert']    = '\cacert.pem';
        $alipay_config['transport']    = 'http';

        $payment_type = "1";
        $notify_url = Yii::$app->urlManager->createAbsoluteUrl('pay/notify');
        $return_url = Yii::$app->urlManager->createAbsoluteUrl('pay/return');
        $out_trade_no = $sn;
        $total_fee = 10;
        $show_url = Yii::$app->urlManager->createAbsoluteUrl(['product/view', 'id' => $firstProduct]);
        $anti_phishing_key = time();
        $exter_invoke_ip = "";

        $parameter = [
            "service" => "create_direct_pay_by_user",
            "partner" => trim($alipay_config['partner']),
            "seller_email" => trim($alipay_config['seller_email']),
            "payment_type"	=> $payment_type,
            "notify_url"	=> $notify_url,
            "return_url"	=> $return_url,
            "out_trade_no"	=> $out_trade_no,
            "subject"	=> $subject,
            "total_fee"	=> $total_fee,
            "body"	=> $body,
            "show_url"	=> $show_url,
            "anti_phishing_key"	=> $anti_phishing_key,
            "exter_invoke_ip"	=> $exter_invoke_ip,
            "_input_charset"	=> trim(strtolower($alipay_config['input_charset'])),
        ];
        $alipaySubmit = new AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter, "post", "正在跳转支付宝付款，请稍候...");

        echo $html_text;

    }

    public function actionNotify()
    {
        $alipay_config['partner'] = Yii::$app->params['alipayPartner'];
        $alipay_config['seller_email'] = Yii::$app->params['alipaySellerEmail'];
        $alipay_config['key'] = Yii::$app->params['alipayKey'];
        $alipay_config['sign_type']    = strtoupper('MD5');
        $alipay_config['input_charset']= strtolower('utf-8');
        $alipay_config['cacert']    = '\cacert.pem';
        $alipay_config['transport']    = 'http';
        Yii::getLogger()->log(print_r($alipay_config, true), Logger::LEVEL_ERROR);
        $notify = new AlipayNotify($alipay_config);
        if ($notify->verifyNotify()) {
            Yii::getLogger()->log('verify Notify success', Logger::LEVEL_ERROR);

            $sn = $_POST['out_trade_no'];
            $tradeNo = $_POST['trade_no'];
            $tradeStatus = $_POST['trade_status'];

            if($tradeStatus == 'TRADE_FINISHED') {
                Yii::getLogger()->log('TRADE_FINISHED: ' . $sn . ' trade_no:' . $tradeNo, Logger::LEVEL_ERROR);
            } else if ($tradeStatus == 'TRADE_SUCCESS') {
                $model = Order::find()->where(['sn' => $sn])->one();
                $model->status = $model->payment_status = Order::PAYMENT_STATUS_PAID;
                $model->payment_name = $tradeNo;
                $model->save();
            }

            //给用户发送邮件
            $result = Yii::$app->mailer->compose('orderPaySuccess', ['username' => $model->consignee, 'order' => $model])
                ->setFrom(Yii::$app->params['customerServiceEmail'])
                ->setFromName(Yii::$app->params['customerServiceName'])
                ->setTo($model->email)
                ->setSubject('您的家家优品订单【' . $model->sn . '】已确认支付，欢迎您随时关注订单状态！')
                ->send();

            Yii::getLogger()->log('pay success mail result: ' . $result, Logger::LEVEL_ERROR);

            echo "success";
        } else {
            Yii::getLogger()->log('verify Notify failed', Logger::LEVEL_ERROR);
            echo "fail";
        }
    }

    public function actionReturn($out_trade_no)
    {
        Yii::$app->session['step'] = 3;

        $model = Order::find()->where(['sn' => $out_trade_no])->one();
        if ($model === null)
            throw new NotFoundHttpException('model does not exist.');

        return $this->render('success', [
            'model' => $model,
        ]);
    }


}