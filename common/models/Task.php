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
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $start_date
 * @property integer $due_date
 * @property integer $assignee
 * @property integer $percent
 * @property string $status
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
        return [
            ['status', 'default', 'value' => self::STATUS_NEW],
            ['status', 'in', 'range' => [self::STATUS_NEW, self::STATUS_INPROGRESS, self::STATUS_DONE, self::STATUS_INVALID]],
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
        if ($format == true) {
            return date($default, $this->created_at);
        }
        return $this->created_at;
    }

    public function getStartDate($format = false, $default = 'F j, Y, g:i a')
    {
        if ($format == true) {
            return date($default, $this->start_date);
        }
        return $this->start_date;
    }

    public function getDueDate($format = false, $default = 'F j, Y, g:i a')
    {
        if ($format == true) {
            return date($default, $this->due_date);
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
}
