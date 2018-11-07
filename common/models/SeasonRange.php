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
}
