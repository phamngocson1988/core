<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\GameVersion;

class CreateGameVersionForm extends Model
{
    public $title;

    public function rules()
    {
        return [
            [['title'], 'required'],
        ];
    }

    public function save()
    {
        $method = new GameVersion();
        $method->title = $this->title;
        return $method->save() ? $method : null;
    }
}
