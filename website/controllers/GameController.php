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
use website\models\Promotion;
use website\models\GameCategoryItem;

// form
use website\forms\FetchGameForm;
use website\components\cart\CartItem;

/**
 * GameController
 */
class GameController extends Controller
{
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

        $group = GameGroup::findOne($model->group_id);
        $games = CartItem::find()->where(['group_id' => $model->group_id])->all();
        $methods = ArrayHelper::getColumn($games, 'method');
        $methods = array_filter($methods);
        $methods = array_unique($methods);

        // Game settings
        $settingMethod = GameSetting::find()->where(['key' => 'method'])->one();
        $settingMethodValues = explode(",", $settingMethod->value);

        $settingVersion = GameSetting::find()->where(['key' => 'version'])->one();
        $settingVersionValues = explode(",", $settingVersion->value);
        $settingVersionKeys = array_map(function($val) {
            return Inflector::slug($val);
        }, $settingVersionValues);
        $settingVersionMapping = array_combine($settingVersionKeys, $settingVersionValues);

        $settingPackage = GameSetting::find()->where(['key' => 'package'])->one();
        $settingPackageValues = explode(",", $settingPackage->value);
        $settingPackageKeys = array_map(function($val) {
            return Inflector::slug($val);
        }, $settingPackageValues);
        $settingPackageMapping = array_combine($settingPackageKeys, $settingPackageValues);

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

        
        $settingMethodParams = [];
        foreach ($settingMethodValues as $settingMethodValue) {
            $methodParts = explode("|", $settingMethodValue);
            $methodTitle = array_shift($methodParts);
            $slugTitle = Inflector::slug($methodTitle);
            if (!in_array($slugTitle, $methods)) continue;
            $methodPrice = array_shift($methodParts);
            $methodSpeed = array_shift($methodParts);
            $methodSafe = array_shift($methodParts);
            $settingMethodParams[$slugTitle] = [
                'name' => $methodTitle,
                'price' => $methodPrice,
                'speed' => $methodSpeed,
                'safe' => $methodSafe,
            ];
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

    	return $this->render('view', [
            'model' => $model,
            'methods' => $methods,
            'settingMethodParams' => $settingMethodParams,
            'mapping' => json_encode($mapping),
            'settingVersionMapping' => json_encode($settingVersionMapping),
            'settingPackageMapping' => json_encode($settingPackageMapping),
            'relatedGames' => $relatedGames,
            'category' => $category,
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
        ]);
    }
}