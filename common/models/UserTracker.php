<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\helpers\ArrayHelper;
use common\models\User;
use common\models\Country;

/**
 * UserTracker model
 */
class UserTracker extends ActiveRecord
{
    const CONTACTS = [
        'facebook' => 'Facebook', 
        'google'  => 'Google',
        'twitter'  => 'Twitter',
        'instagram'  => 'Instagram',
        'whatsapp'  => 'Whatsapp',
        'telegram'  => 'Telegram',
        'line'  => 'Line',
        'skype'  => 'Skype',
        'email' => 'Email',
        'wechat' => 'Wechat',
        'other' => 'Khác'
    ];
    const CHANNELS = [
        'facebook' => 'Facebook', 
        'forum' => 'Forum',
        'ecommerce' => 'E-Commerce',
        'website' => 'Website',
        'chat' => 'Group Chat'
    ];
    const CUSTOMER_STATUS = [
        1 => 'Normal Customer', 
        2 => 'Potential Customer',
        3 => 'Key Customer'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%lead_tracker}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => date('Y-m-d H:i:s')
            ],
        ];
    }

    public function getSaler()
    {
        if (!$this->saler_id) return null;
        return $this->hasOne(User::className(), ['id' => 'saler_id']);
    }

    public function getGame()
    {
        if (!$this->game_id) return null;
        return $this->hasOne(Game::className(), ['id' => 'game_id']);
    }

    public function getCountryName()
    {
        if (!$this->country_code) return '';
        $country = Country::findOne($this->country_code);
        if ($country) return $country->country_name;
    }

    public function getChannelLabels()
    {
        if ($this->channels) {
            $channels = explode(',', $this->channels);
            $titles = [];
            foreach ($channels as $channel) {
                $titles[] = ArrayHelper::getValue(self::CHANNELS, $channel);
            }
            $titles = array_filter($titles);
            return implode(', ', $titles);
        }
        return '';
    }

    public function getContactLabels()
    {
        if ($this->contacts) {
            $contacts = explode(',', $this->contacts);
            $titles = [];
            foreach ($contacts as $contact) {
                $titles[] = ArrayHelper::getValue(self::CONTACTS, $contact);
            }
            $titles = array_filter($titles);
            return implode(', ', $titles);
        }
        return '';
    }
}
