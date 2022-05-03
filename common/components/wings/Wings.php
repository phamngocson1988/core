<?php
namespace common\components\wings;

use Yii;
use yii\base\Model;

class Wings extends Model
{
    private function getToken() 
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://thesuperwings.com/oauth/token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(Yii::$app->params['wings']));
        $output = curl_exec($ch);
        $data = json_decode($output, true);
        // close curl resource to free up system resources
        curl_close($ch);    
        return $data;
    }

    public function notifyStatus($data)
    {
        $orderId = $data['id'];
        $orderStatus = $data['status'];

        $credentials = $this->getToken();
        $tokenType = $credentials['token_type'];
        $token = $credentials['access_token'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://sublink-core.localhost/api/kg/update-order/$orderId");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['status' => $orderStatus]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: $tokenType $token"
        ]);
        $output = curl_exec($ch);
        curl_close($ch);    
        return !!$output;
    }
}