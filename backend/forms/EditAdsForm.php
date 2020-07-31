<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Ads;
use yii\helpers\ArrayHelper;

class EditAdsForm extends Model
{
    public $id;
    public $title;
    public $link;
    public $media_id;
    public $position;
    public $fee;
    public $currency;
    public $start_date;
    public $end_date;
    public $contact_phone;
    public $contact_email;
    public $contact_name;
    public $status;

    protected $_ads;

    public function rules()
    {
        return [
            [['id', 'title', 'link', 'position', 'media_id', 'status', 'start_date', 'end_date', 'contact_phone', 'contact_email', 'contact_name'], 'required'],
            [['fee', 'currency'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('app', 'title'),
            'link' => Yii::t('app', 'link'),
            'position' => Yii::t('app', 'position'),
            'media_id' => Yii::t('app', 'media_id'),
            'start_date' => Yii::t('app', 'start_date'),
            'end_date' => Yii::t('app', 'end_date'),
            'contact_phone' => Yii::t('app', 'contact_phone'),
            'contact_email' => Yii::t('app', 'contact_email'),
            'contact_name' => Yii::t('app', 'contact_name'),
            'fee' => Yii::t('app', 'fee'),
            'currency' => Yii::t('app', 'currency'),
            'status' => Yii::t('app', 'status'),
        ];
    }
    
    public function update()
    {
        $ads = new Ads();
        $ads->title = $this->title;
        $ads->link = $this->link;
        $ads->media_id = $this->media_id;
        $ads->position = $this->position;
        $ads->start_date = $this->start_date;
        $ads->end_date = $this->end_date;
        $ads->fee = $this->fee;
        $ads->currency = $this->currency;
        $ads->contact_phone = $this->contact_phone;
        $ads->contact_email = $this->contact_email;
        $ads->contact_name = $this->contact_name;
        $ads->status = $this->status;
        return $ads->save();
    }

    public function getAds()
    {
        if (!$this->_ads) {
            $this->_ads = Ads::findOne($this->id);
        }
        return $this->_ads;
    }

    public function loadData()
    {
        $ads = $this->getAds();
        $this->title = $ads->title;
        $this->link = $ads->link;
        $this->media_id = $ads->media_id;
        $this->position = $ads->position;
        $this->start_date =$ads->start_date;
        $this->end_date = $ads->end_date;
        $this->fee = $ads->fee;
        $this->currency = $ads->currency;
        $this->contact_phone = $ads->contact_phone;
        $this->contact_email = $ads->contact_email;
        $this->contact_name = $ads->contact_name;
        $this->status = $ads->status;
    }

    public function fetchPosition()
    {
        return Ads::getPositionList();
    }

    public function fetchStatus()
    {
        return Ads::getStatusList();
    }

    public function fetchCurrency()
    {
        return ArrayHelper::getValue(Yii::$app->params, 'currency', []);
    }
}
