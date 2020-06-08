<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\ComplainReason;

class EditComplainReasonForm extends Model
{
    public $id;
    public $title;

    protected $_reason;

    public function rules()
    {
        return [
            [['id', 'title'], 'required'],
        ];
    }

    public function getReason()
    {
        if (!$this->_reason) {
            $this->_reason = ComplainReason::findOne($this->id);
        }
        return $this->_reason;
    }

    public function update()
    {
        $reason = $this->getReason();
        $reason->title = $this->title;
        return $reason->save();
    }

    public function loadData()
    {
        $reason = $this->getReason();
        $this->title = $reason->title;
    }
}
