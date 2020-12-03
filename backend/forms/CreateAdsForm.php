<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Ads;
use yii\helpers\ArrayHelper;

class CreateAdsForm extends Model
{
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
    public $language;


    public function init()
    {
        $languages = array_keys(Yii::$app->params['languages']);
        if (!in_array($this->language, $languages)) {
            $this->language = reset($languages);
        }
    }

    public function rules()
    {
        return [
            [['title', 'link', 'position', 'media_id', 'status', 'start_date', 'end_date', 'contact_phone', 'contact_email', 'contact_name'], 'required'],
            [['fee', 'currency'], 'safe'],
            ['language', 'required'],
            ['language', 'in', 'range' => array_keys(Yii::$app->params['languages'])],
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
            'language' => Yii::t('app', 'language'),
        ];
    }
    
    public function create()
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
        $ads->language = $this->language;
        return $ads->save();
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

    public function fetchLanguages()
    {
        return ArrayHelper::map(Yii::$app->params['languages'], 'code', 'title');
    }

}
