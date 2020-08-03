<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\GamePackage;

class CreateGamePackageForm extends Model
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
        $method = new GamePackage();
        $method->title = $this->title;
        return $method->save() ? $method : null;
    }
}
