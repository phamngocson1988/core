<?php
namespace website\forms;
use Yii;
use yii\base\Model;
use website\models\WhitelistIp;

class BlockIpAccessForm extends Model
{
    public $ip;
    public $blockCountries = ['VN'];

    public function rules()
    {
        return [['ip', 'required']];
    }
    public function run() 
    {
        $settings = Yii::$app->settings;
        if (!$settings->get('WhitelistSettingForm', 'status', false)) {
          return true;
        }
        if (!$this->validate()) return false;
        return $this->checkCountry();
    }

    protected function getCountry()
    {
        try {
          $clientIp = $this->ip;
          $url = "ipinfo.io/$clientIp";
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          $response = curl_exec($ch);
          $payload = json_decode($response, true);
          curl_close($ch);
          return isset($payload['country']) ? $payload['country'] : '';
        } catch (Exception $e) {
          return '';
        }
        
    }

    protected function checkCountry()
    {
        $country = $this->getCountry();
        if (!$country || in_array($country, $this->blockCountries)) {
            return WhitelistIp::find()->where(['ip' => $this->ip, 'status' => WhitelistIp::STATUS_APPROVED])->exists();
        }
        return true;
    }
}