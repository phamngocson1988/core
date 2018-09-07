<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use common\models\User;

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
    const STATUS_NEW = 'new';
    const STATUS_INPROGRESS = 'inprogress';
    const STATUS_DONE = 'done';
    const STATUS_INVALID = 'invalid';


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%task}}';
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

    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getCreatorName()
    {
        $creator = $this->creator;
        if ($creator) {
            return $creator->username;
        }
        return '';
    }

    public function getReceiver()
    {
        return $this->hasOne(User::className(), ['id' => 'assignee']);
    }

    public function getReceiverName()
    {
        $receiver = $this->receiver;
        if ($receiver) {
            return $receiver->username;
        }
        return '';
    }

    public function getCreatedAt($format = false, $default = 'F j, Y, g:i a')
    {
        return $this->created_at;
    }

    public function getStartDate($format = false, $default = 'F j, Y, g:i a')
    {
        return $this->start_date;
    }

    public function getDueDate($format = false, $default = 'F j, Y, g:i a')
    {
        if ($format == true) {
            return date($default, strtotime($this->due_date));
        }
        return $this->due_date;
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_NEW => 'New',
            self::STATUS_INPROGRESS => 'Inprogress',            
            self::STATUS_DONE => 'Done',            
            self::STATUS_INVALID => 'Invalid',            
        ]; 
    }

    public function getStatusLabel()
    {
        $list = self::getStatusList();
        return ArrayHelper::getValue($list, $this->status);
    }

    public function isDelay()
    {
        if (strtotime($this->due_date) >= strtotime('now')) {
            return false;
        } else {
            return !in_array($this->status, [self::STATUS_DONE, self::STATUS_INVALID]);
        }
    }
}
