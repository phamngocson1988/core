<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\User;
use backend\models\Operator;
use yii\helpers\ArrayHelper;
use backend\forms\AssignRoleForm;

class CreateOperatorForm extends Model
{
	public $name;
    public $main_url;
    public $admin_id;
    public $subadmin_ids;
    public $moderator_ids;

    public $_admin;
    public $_subadmins;
    public $_moderators;
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 255],

            ['main_url', 'trim'],
            ['main_url', 'string', 'max' => 255],

            ['admin_id', 'required'],
            ['admin_id', 'validateAdmin'],

            ['subadmin_ids', 'safe'],
            ['subadmin_ids', 'validateSubAdmin'],

            ['moderator_ids', 'safe'],
            ['moderator_ids', 'validateModerator'],
        ];
    }

    public function validateAdmin($attribute, $params = [])
    {
        $user = $this->getAdmin();
        if ($user && $user->hasOperator()) {
            $this->addError($attribute, Yii::t('app', 'user_is_belong_to_other_operator'));
        }   
    }

    public function validateSubAdmin($attribute, $params = [])
    {
        if ($this->hasError()) return;
        $users = $this->getSubAdmins();
        foreach ($users as $user) {
            if ($user->hasOperator()) {
                $this->addError($attribute, sprintf("User %s already has operator", $user->email));
                return;
            }  
        }

        $subs = (array)$this->subadmin_ids;
        $mods = (array)$this->moderator_ids;
        if (count(array_intersect($subs, $mods)) || in_array($this->admin_id, $subs)) {
            $this->addError($attribute, 'One user should be assigned to one role');
            return;
        }
    }

    public function validateModerator($attribute, $params = [])
    {
        if ($this->hasError()) return;
        $users = $this->getModerators();
        foreach ($users as $user) {
            if ($user->hasOperator()) {
                $this->addError($attribute, sprintf("User %s already has operator", $user->email));
                return;
            }  
        }

        $subs = (array)$this->subadmin_ids;
        $mods = (array)$this->moderator_ids;
        if (count(array_intersect($subs, $mods)) || in_array($this->admin_id, $mods)) {
            $this->addError($attribute, 'One user should be assigned to one role');
            return;
        }
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'operator_name'),
            'main_url' => Yii::t('app', 'operator_main_url'),
            'admin_id' => Yii::t('app', 'operator_admin'),
            'subadmin_ids' => Yii::t('app', 'operator_subadmin'),
            'moderator_ids' => Yii::t('app', 'operator_moderator'),
        ];
    }

    public function create()
    {
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $operator = new Operator();
            $operator->name = $this->name;
            $operator->main_url = $this->main_url;
            $operator->save();

            $assignAdminForm = new AssignRoleForm([
                'role' => 'admin',
                'user_id' => $this->admin_id
            ]);
            $assignAdminForm->save();
            $admin = $this->getAdmin();
            $admin->operator_id = $operator->id;
            $admin->save();

            foreach ((array)$this->subadmin_ids as $subId) {
                $assignSubAdminForm = new AssignRoleForm([
                    'role' => 'manager',
                    'user_id' => $subId
                ]);
                $assignSubAdminForm->save();
            }
            $subs = $this->getSubAdmins();
            if ($subs) {
                foreach ((array)$subs as $sub) {
                    $sub->operator_id = $operator->id;
                    $sub->save();
                }
            }

            foreach ((array)$this->moderator_ids as $modId) {
                $assignModeratorForm = new AssignRoleForm([
                    'role' => 'moderator',
                    'user_id' => $modId
                ]);
                $assignModeratorForm->save();
            }
            $mods = $this->getSubAdmins();
            if ($mods) {
                foreach ((array)$mods as $mod) {
                    $mod->operator_id = $operator->id;
                    $mod->save();
                }
            }

            $transaction->commit();
            return $operator;
        } catch(Exception $e) {
            $transaction->rollback();
            $this->addError('name', $e->getMessage());
            return false;
        }
        

        

    }

    public function fetchUsers()
    {
        // $auth = Yii::$app->authManager;
        // $userIds = $auth->getUserIdsByRole('admin');
        $users = User::find()
        ->where(['IS', 'operator_id', null])
        ->select(['id', 'email'])
        ->all();
        return ArrayHelper::map($users, 'id', 'email');
    }

    public function getAdmin()
    {
        if (!$this->_admin && $this->admin_id) {
            $this->_admin = User::findOne($this->admin_id);
        }
        return $this->_admin;
    }

    public function getSubAdmins()
    {
        if (!$this->_subadmins) {
            $this->_subadmins = User::findAll($this->subadmin_ids);
        }
        return $this->_subadmins;
    }

    public function getModerators()
    {
        if (!$this->_moderators) {
            $this->_moderators = User::findAll($this->moderator_ids);
        }
        return $this->_moderators;
    }
}
