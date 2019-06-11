<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Contact;
use common\models\Group;
use common\models\ContactGroup;
use yii\helpers\ArrayHelper;

/**
 * FetchContactForm
 */
class FetchContactForm extends Model
{
    public $q;
    public $user_id;
    public $group_ids;
    private $_command;

    public function rules()
    {
        return [
            [['q', 'user_id'], 'trim'],
            ['group_ids', 'safe']
        ];
    }

    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = Contact::find();
        $table = Contact::tableName();
        $groupTable = ContactGroup::tableName();
        $command->where(["$table.user_id" => $this->user_id]);

        if ($this->q) {
            $command->andWhere(['or',
                ['like', "$table.phone", $this->q],
                ['like', "$table.name", $this->q]
            ]);
        }
        if ($this->group_ids) {
            $command->leftJoin($groupTable, "{$groupTable}.contact_id = {$table}.id");
            $command->andWhere(['IN', "$groupTable.group_id", (array)$this->group_ids]);        
        }
        $command->with('groups');
        $this->_command = $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }

    public function fetchGroups()
    {
        return ArrayHelper::map(Group::find()->all(), 'id', 'name');
    }
}
