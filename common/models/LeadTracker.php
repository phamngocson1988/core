<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use common\models\User;
use yii\behaviors\TimestampBehavior;

/**
 * Customer model
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $created_by
 * @property datetime $created_at
 * @property datetime $updated_at
 * @property datetime $start_date
 * @property datetime $due_date
 * @property integer $assignee
 * @property integer $percent
 * @property enum $status
 */
class Task extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%lead_tracker}}';
    }

    public function rules()
    {
        $statusList = self::getStatusList();
        $statusKeys = array_keys($statusList);
        return [
            ['status', 'default', 'value' => self::STATUS_NEW],
            ['status', 'in', 'range' => $statusKeys],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => date('Y-m-d H:i:s')
            ],
        ];
    }

}
