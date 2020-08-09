<?php
/**
 * @link https://github.com/yiiviet/yii2-payment
 * @copyright Copyright (c) 2017 Yii Viet
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace yiiviet\payment\baokim;

use vxm\gatewayclients\RequestData as BaseRequestData;

/**
 * Lớp RequestData cung cấp dữ liệu đã được kiểm tra tính trọn vẹn khi tạo [[request()]] ở [[PaymentGateway]].
 *
 * @method PaymentClient getClient() đối tượng client đã dùng để thực thi request.
 *
 * @property PaymentClient $client đối tượng client đã dùng để thực thi request.
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0
 */
class RequestData extends BaseRequestData
{

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['business'], 'required', 'on' => [PaymentGateway::RC_PURCHASE, PaymentGateway::RC_GET_MERCHANT_DATA]],
            [['merchant_id', 'transaction_id'], 'required', 'on' => PaymentGateway::RC_QUERY_DR],
            [['checksum'], 'required', 'on' => [PaymentGateway::RC_QUERY_DR, PaymentGateway::RC_PURCHASE], 'when' => function () {
                if ($this->command === PaymentGateway::RC_PURCHASE) {
                    return !$this->getClient()->getGateway()->pro;
                } else {
                    return true;
                }
            }],
            [['signature'], 'required', 'on' => [PaymentGateway::RC_GET_MERCHANT_DATA, PaymentGateway::RC_PURCHASE], 'when' => function () {
                if ($this->command === PaymentGateway::RC_PURCHASE) {
                    return $this->getClient()->getGateway()->pro;
                } else {
                    return true;
                }
            }],
            [['order_id', 'total_amount', 'url_success'], 'required', 'on' => [PaymentGateway::RC_PURCHASE]],
            [['payer_name', 'payer_email', 'payer_phone_no', 'bank_payment_method_id'], 'required', 'on' => PaymentGateway::RC_PURCHASE, 'when' => function () {
                return $this->getClient()->getGateway()->pro;
            }]
        ];

    }

    /**
     * @inheritdoc
     * @throws \yii\base\NotSupportedException
     */
    protected function ensureAttributes(array &$attributes)
    {
        $client = $this->getClient();
        $command = $this->getCommand();

        if (in_array($command, [PaymentGateway::RC_PURCHASE, PaymentGateway::RC_GET_MERCHANT_DATA], true)) {
            $attributes['business'] = $attributes['business'] ?? $client->merchantEmail;
        }

        if ($command === PaymentGateway::RC_GET_MERCHANT_DATA || ($command === PaymentGateway::RC_PURCHASE && $client->getGateway()->pro)) {
            unset($attributes['signature']);
            ksort($attributes);

            if ($command === PaymentGateway::RC_PURCHASE) {
                $strSign = 'POST' . '&' . urlencode(PaymentGateway::PURCHASE_PRO_URL) . '&&' . urlencode(http_build_query($attributes));
            } else {
                $strSign = 'GET' . '&' . urlencode(PaymentGateway::PRO_SELLER_INFO_URL) . '&' . urlencode(http_build_query($attributes)) . '&';
            }

            $signature = $client->signature($strSign, PaymentClient::SIGNATURE_RSA);
            $attributes['signature'] = base64_encode($signature);
        } elseif (in_array($command, [PaymentGateway::RC_PURCHASE, PaymentGateway::RC_QUERY_DR], true)) {
            $attributes['merchant_id'] = $client->merchantId;
            unset($attributes['checksum']);
            ksort($attributes);
            $strSign = implode("", $attributes);
            $attributes['checksum'] = $client->signature($strSign, PaymentClient::SIGNATURE_HMAC);
        }

        parent::ensureAttributes($attributes);
    }

}
