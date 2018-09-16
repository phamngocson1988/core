<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\SystemLog;

/**
 * CreateSystemLogForm
 */
class CreateSystemLogForm extends Model
{
    public $user_id;
    public $action;
    public $description;
    public $data;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'action', 'description'], 'trim'],
            [['data'], 'safe'],
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            $log = new SystemLog();
            $log->user_id = $this->user_id;
            $log->action = $this->action;
            $log->setData($this->data);
            $log->created_at = date('Y-m-d H:i:s');
            $log->save();
        }
    }
}
