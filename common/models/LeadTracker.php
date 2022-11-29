<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use common\models\User;
use common\models\Country;

/**
 * LeadTracker model
 */
class LeadTracker extends ActiveRecord
{
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
                'createdAtAttribute' => 'updated_by',
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

    public function getCountryName()
    {
        if (!$this->country_code) return '';
        $country = Country::findOne($this->country_code);
        if ($country) return $country->country_name;
    }
}
