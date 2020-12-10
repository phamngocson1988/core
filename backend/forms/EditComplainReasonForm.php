<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\ComplainReason;
use common\components\helpers\LanguageHelper;

class EditComplainReasonForm extends Model
{
    public $id;
    public $title;
    public $language;

    protected $_reason;

    public function rules()
    {
        return [
            [['id', 'title'], 'required'],
            ['language', 'safe']
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
        $this->language = $reason->language;
    }

    public function fetchLanguages()
    {
        return LanguageHelper::fetchLanguages();
    }
}
