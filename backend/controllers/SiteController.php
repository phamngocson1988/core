<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\forms\LoginForm;
use backend\forms\ActivateUserForm;
use backend\models\Order;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'activate'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'email', 'sql', 'report-revenue'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $command = Order::find()->where(['status' => Order::STATUS_CONFIRMED]);

        $topCustomers = Order::find()
        ->select(['customer_id', 'customer_name', 'sum(total_price) as total_price'])
        ->where(['status' => Order::STATUS_CONFIRMED])
        ->groupBy('customer_id')
        ->orderBy('total_price desc')
        ->limit(5)
        ->asArray()
        ->all();
        $topGames = Order::find()
        ->select(['game_id', 'game_title', 'sum(total_price) as total_price'])
        ->where(['status' => Order::STATUS_CONFIRMED])
        ->groupBy('game_id')
        ->orderBy('total_price desc')
        ->limit(5)
        ->asArray()
        ->all();
        $data = [
            'revenue' => $command->sum('total_price'),
            'quantity' => $command->sum('quantity'),
            'orders' => $command->count(),
            'games' => $command->count('DISTINCT game_id'),
            'customers' => $command->count('DISTINCT customer_id'),
            'topCustomers' => $topCustomers,
            'topGames' => $topGames
        ];
        return $this->render('index', $data);
    }


    public function actionReportRevenue() 
    {
        $command = Order::find()->where(['status' => Order::STATUS_CONFIRMED]);
        $weekData = [12, 19, 3, 5, 2, 3, 10];
        $monthData = [1,2,3,4,5,6,7,8,9,10,11,12];
        $type = Yii::$app->request->post('type', 'today');
        $data = [];
        switch ($type) {
            case 'today':
                $result = $command->andWhere(['>=', 'confirmed_at', date('Y-m-d 00:00:00')])->sum('total_price');
                $data = [$result];
                break;
            case 'lastday':
                $lastDay = date('Y-m-d', strtotime("-1 days"));
                $result = $command->andWhere(['BETWEEN', 'confirmed_at', "$lastDay 00:00:00", "$lastDay 23:59:59"])->sum('total_price');
                $data = [$result];
                break;

            case 'week':
                $startDate = date('Y-m-d', strtotime('last sunday'));
                $endDate = date('Y-m-d 23:59:59');
                $result = $command
                ->select(['DATE(confirmed_at) as report_date', 'SUM(total_price) as total_price'])
                ->andWhere(['BETWEEN', 'confirmed_at', $startDate, $endDate])
                ->groupBy(['report_date'])
                ->orderBy(['confirmed_at' => SORT_ASC])
                ->asArray()
                ->all();
                $data = array_map(function($row) {
                    return $row['total_price'];
                }, $result);
                break;
                break;
            case 'month':
                $startDate = date('Y-m-01 00:00:00');
                $endDate = date('Y-m-d 23:59:59');
                $result = $command
                ->select(['DATE(confirmed_at) as report_date', 'SUM(total_price) as total_price'])
                ->andWhere(['BETWEEN', 'confirmed_at', $startDate, $endDate])
                ->groupBy(['report_date'])
                ->orderBy(['confirmed_at' => SORT_ASC])
                ->asArray()
                ->all();
                $data = array_map(function($row) {
                    return $row['total_price'];
                }, $result);
                break;
            
            default:
                # code...
                break;
        }
        // $data = $type === 'month' ? $monthData : $weekData;

        return json_encode($data);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        $this->layout = 'login';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login.tpl', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
    public function actionActivate()
    {
        $this->layout = 'login.tpl';
        $request = Yii::$app->request;
        $id = $request->get('id');
        $key = $request->get('activation_key');
        $model = new ActivateUserForm(['id' => $id, 'activation_key' => $key]);
        if ($request->isPost) {
            $model->setScenario(ActivateUserForm::SCENARIO_CREATE_PASS);
            if ($model->load($request->post()) && $user = $model->activate()) {
                // login
                $loginForm = new LoginForm(['username' => $user->username, 'password' => $model->password]);
                if ($loginForm->login()) {
                    Yii::$app->session->setFlash('success', 'Success!');
                    return $this->goHome();
                } else {
                    Yii::$app->session->setFlash('error', $loginForm->getErrorSummary(true));
                }
            } else {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }
        } else {
            $model->setScenario(ActivateUserForm::SCENARIO_CHECK_KEY);
            if (!$model->validate()) {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }
        }

        return $this->render('activate.tpl', ['model' => $model]);
    }

    public function actionEmail($email)
    {
        $settings = Yii::$app->settings;
        $adminEmail = $settings->get('ApplicationSettingForm', 'admin_email', null);
        Yii::$app->supplier_mailer->compose('test_mail')
            ->setTo($email)
            ->setFrom([$adminEmail => Yii::$app->name . ' Administrator'])
            ->setSubject(sprintf("TESTING EMAIL"))
            ->setTextBody("Thanks for your deposit")
            ->send();
        var_dump($email);die;
    }

    public function actionSql()
    {
        $request = Yii::$app->request;
        $model = new \backend\forms\ExecuteSqlForm();
        if ($request->isPost) {
            if ($model->load($request->post()) && $result = $model->run()) {
                Yii::$app->session->setFlash('success', 'Success!');
                echo '<pre>';
                var_dump($result);
                echo '</pre>';

            } else {
                $messages = $model->getErrorSummary(true);
                Yii::$app->session->setFlash('error', reset($messages));
            }
        } 
         
        return $this->render('sql', ['model' => $model]);
    }
}
