<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\GamePackage;

class CreateGamePackageForm extends Model
{
    public $group_id;
    public $title;

    public function rules()
    {
        return [
            [['title', 'group_id'], 'required'],
        ];
    }

    public function save()
    {
        $method = new GamePackage();
        $method->title = $this->title;
        $method->group_id = $this->group_id;
        return $method->save() ? $method : null;
    }
}
