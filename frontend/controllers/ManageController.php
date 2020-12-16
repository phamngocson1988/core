<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use frontend\models\Operator;
use frontend\models\OperatorFavorite;
use frontend\models\OperatorReview;
use frontend\models\OperatorStaff;
use frontend\models\Complain;

class ManageController extends Controller
{
    protected $operator_id;
    protected $_operator;
    protected $_isAdmin;

    protected $_actions = [];
    protected $_onlyAdminActions = [];

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => array_merge($this->_actions, $this->_onlyAdminActions),
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            $user = Yii::$app->user->getIdentity();
                            $request = Yii::$app->request;
                            $operator_id = $request->get('operator_id');
                            if (!$operator_id) return false;

                            if (in_array($action, $this->_onlyAdminActions)) {
                                return $user->isOperatorStaffOf($operator_id, OperatorStaff::ROLE_ADMIN);
                            } else {
                                return $user->isOperatorStaffOf($operator_id);
                            }
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'assign-complain' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) return false;
        $request = Yii::$app->request;
        $this->operator_id = $request->get('operator_id');
        $operator = $this->getOperator();
        return true;
    }

    protected function getOperator()
    {
        if (!$this->_operator) {
            $this->_operator = Operator::findOne($this->operator_id);
        }
        return $this->_operator;
    }

    protected function isAdmin() 
    {
        if ($this->_isAdmin === null) {
            $user = Yii::$app->user->identity;
            $this->_isAdmin = $user->isOperatorStaffOf($this->operator_id, OperatorStaff::ROLE_ADMIN);
        }
        return $this->_isAdmin;
    }
}