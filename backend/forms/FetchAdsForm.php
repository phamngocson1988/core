<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Ads;

class FetchAdsForm extends Model
{
    public $position;
    public $status;
    public $contact_email;

    private $_command;
    
    public function attributeLabels()
    {
        return [
            'position' => Yii::t('app', 'position'),
            'status' => Yii::t('app', 'status'),
            'contact_email' => Yii::t('app', 'contact_email'),
        ];
    }
    protected function createCommand()
    {
        $command = Ads::find();

        if ($this->position) {
            $command->andWhere(['position' => $this->position]);
        }
        if ($this->status) {
            $command->andWhere(['status' => $this->status]);
        }
        if ($this->contact_email) {
            $command->andWhere(['like', 'contact_email', $this->contact_email]);
        }
        $this->_command = $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }

    public function fetchPosition()
    {
        return Ads::getPositionList();
    }

    public function fetchStatus()
    {
        return Ads::getStatusList();
    }
}
