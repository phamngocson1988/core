<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%season_range}}".
 *
 * @property int $id
 * @property int $season_id
 * @property string $range_type
 * @property string $range_data
 * @property int $prev_date
 * @property int $next_date
 * @property int $apply_discount
 * @property string $status
 * @property string $note
 */
class SeasonRange extends \yii\db\ActiveRecord
{
    const TYPE_WEEKLY = 'weekly';
    const TYPE_MONTHLY = 'monthly';
    const TYPE_YEARLY = 'yearly';
    const TYPE_SPECIFIED_DATE = 'specified_date';
    const TYPE_SPECIAL_DATE = 'special_date';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%season_range}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['season_id'], 'required'],
            [['season_id', 'prev_date', 'next_date', 'apply_discount'], 'integer'],
            [['range_type', 'range_data', 'status'], 'string'],
            [['note'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'season_id' => Yii::t('app', 'Season ID'),
            'range_type' => Yii::t('app', 'Range Type'),
            'range_data' => Yii::t('app', 'Range Data'),
            'prev_date' => Yii::t('app', 'Prev Date'),
            'next_date' => Yii::t('app', 'Next Date'),
            'apply_discount' => Yii::t('app', 'Apply Discount'),
            'status' => Yii::t('app', 'Status'),
            'note' => Yii::t('app', 'Note'),
        ];
    }

    public static function handlers()
    {
        return = [
            self::TYPE_WEEKLY => [
                'name' => Yii::t('app', 'weekly'),
                'class' => 'WeeklySeasonTypeHander'
            ],
            self::TYPE_MONTHLY => [
                'name' => Yii::t('app', 'monthLY'),
                'class' => 'MonthlySeasonTypeHander'
            ],
        ];
    }

    public function getHandler()
    {
        $handlers = self::handlers();
        $setting = ArrayHelper::getValue($handlers, $this->range_type, []);
        if ($setting) {
            $class = ArrayHelper::getValue($setting, 'class');
            return new $class($this->attributes);
        } else {
            return null;
        }
    }

    /**
     * Check if the date belongs to this range
     * @param $date string date format (Y-m-d)
     * @return boolean true if the date is in the range
     */
    public function isInRange($date)
    {
        return true;
    }
}

class WeeklySeasonTypeHander extends SeasonRange
{
    protected $delimiter = "|";

    public function isInRange($date)
    {
        $D = date('D', $date);
        $data = $this->getData();
        return in_array($D, $data);
    }

    public function getData()
    {
        return explode($this->delimiter, $this->range_data);
    }

    public function getAvailableData()
    {
        return ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    }
}

class MonthlySeasonTypeHander extends SeasonRange
{
    protected $delimiter = "|";

    public function isInRange($date)
    {
        $d = date('d', $date);
        $data = $this->getData();
        return in_array($d, $data);
    }

    public function getData()
    {
        return explode($this->delimiter, $this->range_data);
    }
}
