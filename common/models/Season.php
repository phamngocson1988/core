<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%season}}".
 *
 * @property int $id
 * @property string $name
 * @property string $descripition
 * @property int $apply_discount
 * @property string $status
 * @property string $created_at
 * @property int $created_by
 */
class Season extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%season}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'created_at', 'created_by'], 'required'],
            [['descripition', 'status'], 'string'],
            [['apply_discount', 'created_by'], 'integer'],
            [['created_at'], 'safe'],
            [['name'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'descripition' => Yii::t('app', 'Descripition'),
            'apply_discount' => Yii::t('app', 'Apply Discount'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }
}
