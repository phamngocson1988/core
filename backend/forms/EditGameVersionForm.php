<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\GameVersion;

class EditGameVersionForm extends Model
{
    public $id;
    public $title;

    protected $_method;

    public function rules()
    {
        return [
            [['id', 'title'], 'required'],
        ];
    }

    public function save()
    {
        $method = $this->getVersion();
        $method->title = $this->title;
        return $method->save() ? $method : null;
    }

    public function getVersion()
    {
        if (!$this->_method) {
            $this->_method = GameVersion::findOne($this->id);
        }
        return $this->_method;
    }

    public function loadData()
    {
        $method = $this->getVersion();
        $this->title = $method->title;
    }
}
