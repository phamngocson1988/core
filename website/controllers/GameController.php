<?php
namespace website\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use website\models\Game;
use website\models\GameGroup;
use website\models\GameSetting;
use website\models\GameMethod;
use website\models\GameVersion;
use website\models\GamePackage;
use website\models\Promotion;
use website\models\GameCategoryItem;

// form
use website\forms\FetchGameForm;
use website\components\cart\CartItem;
use website\models\GameSubscriber;

/**
 * GameController
 */
class GameController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'hot-deal', 'top-grossing', 'new-trending'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['quick'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'game.index';
        $request = Yii::$app->request;
        $form = new FetchGameForm([
            'q' => $request->get('q'),
            'category_id' => $request->get('category_id'),
        ]);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form
        ]);
    }
    public function actionView($id)
    {
    	$request = Yii::$app->request;
        $model = CartItem::findOne($id);

        if (!$model->group_id) {
            $games = [];
            $methods = $versions = $packages = [];
        } else {
            $group = GameGroup::findOne($model->group_id);
            $games = CartItem::find()->where(['group_id' => $model->group_id])->all();
            $methodIds = ArrayHelper::getColumn($games, 'method');
            $methodIds = array_filter($methodIds);
            $methodIds = array_unique($methodIds);
            $methods = GameMethod::findAll($methodIds);

            $versionIds = ArrayHelper::getColumn($games, 'version');
            $versionIds = array_filter($versionIds);
            $versionIds = array_unique($versionIds);
            $versions = GameVersion::findAll($versionIds);

            $packageIds = ArrayHelper::getColumn($games, 'package');
            $packageIds = array_filter($packageIds);
            $packageIds = array_unique($packageIds);
            $packages = GamePackage::findAll($packageIds);
        }

        // Game settings
        $settingVersionMapping = ArrayHelper::map($versions, 'id', 'title');
        $settingPackageMapping = ArrayHelper::map($packages, 'id', 'title');

        $mapping = [];
        foreach ($games as $game) {
            $gameInfo = [
                'viewUrl' => Url::to(['game/view', 'id' => $game->id, 'slug' => $game->slug], true),
                'cartUrl' => Url::to(['cart/add', 'id' => $game->id], true),
                'calculateUrl' => Url::to(['cart/calculate', 'id' => $game->id], true),
                'title' => $game->title,
                'content' => $game->content,
                'image' => $game->getImageUrl(),
                'save' => sprintf('save %s', number_format($game->getSavedPrice())) . '%',
            ];
            $mapping[$game->method][$game->version][$game->package] = $gameInfo;
        }

        
        // other games
        $relatedGames = [];
        $category = null;
        if ($model->hasCategory()) {
            $categories = $model->categories;
            $categoryIds = ArrayHelper::getColumn($categories, 'id');
            $category =  reset($categories);
            $categoryGames = GameCategoryItem::find()
            ->where(['in', 'category_id', $categoryIds])
            ->andWhere(['<>', 'game_id', $id])
            ->limit(5)->all();
            $gameIds = ArrayHelper::getColumn($categoryGames, 'game_id');
            $relatedGames = Game::findAll($gameIds);
        }

        // reseller
        $is_reseller = false;
        if (!Yii::$app->user->isGuest) {
            $user = Yii::$app->user->identity;
            $is_reseller = $user->isReseller();
        }

        // Subscribe
        $isSubscribe = Yii::$app->user->isGuest ? false : GameSubscriber::find()->where([
            'user_id' => Yii::$app->user->id,
            'game_id' => $id
        ])->exists();

    	return $this->render('view', [
            'model' => $model,
            'methods' => $methods,
            'mapping' => json_encode($mapping),
            'settingVersionMapping' => json_encode($settingVersionMapping),
            'settingPackageMapping' => json_encode($settingPackageMapping),
            'relatedGames' => $relatedGames,
            'category' => $category,
            'is_reseller' => $is_reseller,
            'has_group' => (int)$model->group_id,
            'isSubscribe' => $isSubscribe
        ]);
    }

    public function actionHotDeal()
    {
        $form = new FetchGameForm(['hot_deal' => Game::HOT_DEAL]);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form
        ]);
    }

    public function actionTopGrossing()
    {
        $form = new FetchGameForm(['top_grossing' => Game::TOP_GROSSING]);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('top-grossing', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form
        ]);
    }

    public function actionNewTrending()
    {
        $form = new FetchGameForm(['new_trending' => Game::NEW_TRENDING]);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('new-trending', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form
        ]);
    }

    public function actionQuick($id, $slug)
    {
        $request = Yii::$app->request;
        $initData = [];
        if ($request->isPost) {
            $file = \yii\web\UploadedFile::getInstanceByName('excel');
            $objPHPExcel = \PHPExcel_IOFactory::load($file->tempName);
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
            foreach ($sheetData as $rowIndex => $row) {
                if ($rowIndex >= 5 && $row['C'] && $row['D']) {
                    $content = [$row['D'], $row['C']];
                    $initData[] = $content;
                }
            }
            
        } else {
            $initData[] = [1, ''];
        }
        $model = CartItem::findOne($id);
        $versions = GameSetting::fetchVersion();
        $packages = GameSetting::fetchPackage();
        $version = ArrayHelper::getValue($versions, $model->version, '');
        $package = ArrayHelper::getValue($packages, $model->package, '');
        $user = Yii::$app->user->getIdentity();
        $balance = $user->getWalletAmount();
        return $this->render('quick', [
            'model' => $model,
            'version' => $version,
            'package' => $package,
            'balance' => $balance,
            'initData' => array_filter($initData)
        ]);
    }
}