<?php

namespace supplier\components\logs;

use Yii;
use supplier\forms\CreateSystemLogForm;
use yii\base\Model;

class SystemLog extends Model
{
    public function log($action, $description, $data = null) 
    {
        $log = [
            'user_id' => Yii::$app->user->id,
            'action' => $action,
            'description' => $description,
            'data' => $data
        ];
        $form = new CreateSystemLogForm($log);
        $form->save();
    }
}
