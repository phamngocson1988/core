<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\WhitelistIp;

class AccessRequestActionForm extends Model
{
    public $ip;
    public $action;
    private $_record;

    public function rules()
    {
        return [
            [['ip', 'action'], 'required'],
        ];
    }

    protected function getRecord() {
        if (!$this->_record) {
          $this->_record = WhitelistIp::findOne($this->ip);
        }
        return $this->_record;
    }

    public function run()
    {
      if (!$this->validate()) return false;
      if ($this->action === 'approve') {
        return $this->approve();
      } elseif ($this->action === 'delete') {
        return $this->delete();
      }
      return true;
    }

    public function approve()
    {
        $record = $this->getRecord();
        if (!$record) return true;
        $record->status = WhitelistIp::STATUS_APPROVED;
        return $record->save();
    }
    public function delete()
    {
        $record = $this->getRecord();
        if (!$record) return true;
        return $record->delete();
    }
}
