<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\User;

/**
 * SystemLog model
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $action
 * @property string $description
 * @property string $data
 * @property integer $created_at
 */
class SystemLog extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%system_log}}';
    }

    public function getCreatedAt($format = false, $default = 'F j, Y, g:i a')
    {
        if ($format == true) {
            return date($default, $this->created_at);
        }
        return $this->created_at;
    }


    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getUsername()
    {
        $user = $this->user;
        if ($user) {
            return $user->username;
        }
        return '';
    }

    public function getData()
    {
        return json_decode($this->data);
    }

    public function setData($data)
    {
        $this->data = json_encode($data);
    }

    public static function getListActions()
    {
        return [
            
        ];
    }
}
