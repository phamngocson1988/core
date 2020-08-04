<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Game;
use backend\models\GameCategory;
use backend\models\GameCategoryItem;
use backend\models\GameGroup;
use backend\models\GameSetting;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

class EditGameForm extends Model
{
    public $id;
    public $title;
    public $excerpt;
    public $content;
    public $unit_name;
    public $status;
    public $image_id;
    public $reseller_price;
    public $original_price;
    public $pack;
    public $pin;
    public $soldout;
    public $average_speed;
    public $number_supplier;
    public $remark;
    public $price_remark;
    public $google_ads;
    public $promotion_info;
    public $event_info;
    public $meta_title;
    public $meta_keyword;
    public $meta_description;
    public $hot_deal;
    public $new_trending;
    public $top_grossing;
    public $back_to_stock;
    public $group_id;
    public $method;
    public $version;
    public $package;
    public $categories;

    protected $_game;
    protected $_groups;

    public function rules()
    {
        return [
            [['id', 'title', 'content', 'unit_name', 'pack'], 'required'],
            ['status', 'default', 'value' => Game::STATUS_VISIBLE],
            [['image_id', 'excerpt', 'units', 'reseller_price'], 'safe'],
            ['pack', 'default', 'value' => 1],
            ['pin', 'default', 'value' => Game::UNPIN],
            ['soldout', 'default', 'value' => 0],
            [['original_price'], 'trim'],
            [['meta_title', 'meta_keyword', 'meta_description', 'promotion_info', 'event_info'], 'trim'],
            [['average_speed', 'number_supplier', 'remark', 'price_remark', 'google_ads', 'categories'], 'safe'],
            [['hot_deal', 'new_trending', 'top_grossing', 'back_to_stock'], 'safe'],
            [['group_id', 'method', 'package', 'version'], 'safe'],
            ['group_id', 'validateGroup']
        ];
    }

    public function validateGroup($attribute, $params) 
    {   
        if (!$this->group_id) return;
        if (!$this->method || !$this->version || !$this->package) {
            $this->addError($attribute, 'Những thông số đi kèm không được bỏ trống');
            return;
        }
        $game = $this->getGame();
        if (
            $game->group_id != $this->group_id
            || $game->method != $this->method
            || $game->version != $this->version
            || $game->package != $this->package
        ) {
            if (Game::find()->where([
                'group_id' => $this->group_id,
                'method' => $this->method,
                'version' => $this->version,
                'package' => $this->package,
            ])->exists()) {
                $this->addError($attribute, 'Những thông số này đã được dùng cho 1 game khác.');
            }
        }

    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('app', 'title'),
            'excerpt' => Yii::t('app', 'excerpt'),
            'content' => Yii::t('app', 'content'),
            'unit_name' => 'Tên đơn vị game',
            'status' => 'Trạng thái sản phẩm',
            'image_id' => 'Hình ảnh',
            'reseller_price' => 'Giá dành cho đại lý',
            'original_price' => 'Giá gốc (Kcoin) / gói game',
            'pack' => 'Số đơn vị game trong gói',
            'pin' => 'Ưu tiên hiển thị',
            'soldout' => 'Hết hàng',
            'average_speed' => 'Tốc độ xử lý (phút)',
            'number_supplier' => 'Số nhà cung cấp',
            'remark' => 'Remark',
            'price_remark' => 'Remark',
            'google_ads' => 'Google Ads',
            'categories' => 'Danh mục game',
        ];
    }

    public function save()
    {
        $transaction = Yii::$app->db->beginTransaction();
        $game = $this->getGame();
        try {
            $game->title = $this->title;
            $game->excerpt = $this->excerpt;
            $game->content = $this->content;
            $game->unit_name = $this->unit_name;
            $game->status = $this->status;
            $game->image_id = $this->image_id;
            $game->reseller_price = $this->reseller_price;
            $game->original_price = $this->original_price;
            $game->pack = $this->pack;
            $game->pin = $this->pin;
            $game->soldout = $this->soldout;
            $game->average_speed = $this->average_speed;
            $game->number_supplier = $this->number_supplier;
            $game->remark = $this->remark;
            $game->price_remark = $this->price_remark;
            $game->google_ads = $this->google_ads;
            $game->promotion_info = $this->promotion_info;
            $game->event_info = $this->event_info;
            $game->meta_title = $this->meta_title;
            $game->meta_keyword = $this->meta_keyword;
            $game->meta_description = $this->meta_description;
            $game->hot_deal = $this->hot_deal;
            $game->top_grossing = $this->top_grossing;
            $game->new_trending = $this->new_trending;
            $game->back_to_stock = $this->back_to_stock;
            $game->group_id = $this->group_id;
            $game->method = $this->method;
            $game->version = $this->version;
            $game->package = $this->package;
            $game->save();
            $newId = $game->id;

            // categories
            $categories = array_filter((array)$this->categories);
            GameCategoryItem::deleteAll(['game_id' => $this->id]);
            foreach ($categories as $key => $categoryId) {
                $postCategory = new GameCategoryItem();
                $postCategory->game_id = $newId;
                $postCategory->category_id = $categoryId;
                $postCategory->save();
            }

            $transaction->commit();
            return $newId;
        } catch (Exception $e) {
            $transaction->rollBack();                
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    protected function getGame()
    {
        if (!$this->_game) {
            $this->_game = Game::findOne($this->id);
        }
        return $this->_game;
    }

    public function loadData()
    {
        $game = $this->getGame();
        $this->title = $game->title;
        $this->excerpt = $game->excerpt;
        $this->content = $game->content;
        $this->unit_name = $game->unit_name;
        $this->status = $game->status;
        $this->image_id = $game->image_id;
        $this->reseller_price = $game->reseller_price;
        $this->original_price = $game->original_price;
        $this->pack = $game->pack;
        $this->pin = $game->pin;
        $this->soldout = $game->soldout;
        $this->average_speed = $game->average_speed;
        $this->number_supplier = $game->number_supplier;
        $this->remark = $game->remark;
        $this->price_remark = $game->price_remark;
        $this->google_ads = $game->google_ads;
        $this->promotion_info = $game->promotion_info;
        $this->event_info = $game->event_info;
        $this->meta_title = $game->meta_title;
        $this->meta_keyword = $game->meta_keyword;
        $this->meta_description = $game->meta_description;
        $this->hot_deal = $game->hot_deal;
        $this->top_grossing = $game->top_grossing;
        $this->new_trending = $game->new_trending;
        $this->back_to_stock = $game->back_to_stock;
        $this->group_id = $game->group_id;
        $this->method = $game->method;
        $this->version = $game->version;
        $this->package = $game->package;

        $categories = GameCategoryItem::find()->where(['game_id' => $this->id])->all();
        $this->categories = ArrayHelper::getColumn($categories, 'category_id');
    }

    public function getCategories($format = '%s')
    {
        $categories = GameCategory::find()->all();

        $categories = ArrayHelper::map($categories, 'id', 'name');
        $categories = array_map(function($name) use ($format) {
            return sprintf($format, $name);
        }, $categories);
        return $categories;
    }

    public function getGroups()
    {
        $groups = GameGroup::find()->all();
        return ArrayHelper::map($groups, 'id', 'title');
    }

    public function getMethods()
    {
        if (!$this->group_id) return [];
        $group = GameGroup::findOne($this->group_id);
        $methods = $group->getMethods();
        return ArrayHelper::map($methods, 'id', 'title');
    }

    public function getVersions()
    {
        if (!$this->group_id) return [];
        $group = GameGroup::findOne($this->group_id);
        $versions = $group->getVersions();
        return ArrayHelper::map($versions, 'id', 'title');
    }

    public function getPackages()
    {
        if (!$this->group_id) return [];
        $group = GameGroup::findOne($this->group_id);
        $packages = $group->packages;
        return ArrayHelper::map($packages, 'id', 'title');
    }

    // public function getMethods()
    // {
    //     if (!$this->group_id) return [];
    //     $group = GameGroup::findOne($this->group_id);
    //     $methods = explode(',', $group->method);
    //     $keys = array_map(function($val) {
    //         return Inflector::slug($val);
    //     }, $methods);
    //     return array_combine($keys, $methods);
    // }

    // public function getVersions()
    // {
    //     if (!$this->group_id) return [];
    //     $group = GameGroup::findOne($this->group_id);
    //     $versions = explode(',', $group->version);
    //     $keys = array_map(function($val) {
    //         return Inflector::slug($val);
    //     }, $versions);
    //     return array_combine($keys, $versions);
    // }

    // public function getPackages()
    // {
    //     if (!$this->group_id) return [];
    //     $group = GameGroup::findOne($this->group_id);
    //     $packages = explode(',', $group->package);
    //     $keys = array_map(function($val) {
    //         return Inflector::slug($val);
    //     }, $packages);
    //     return array_combine($keys, $packages);
    // }


    public function getGroupData()
    {
        $groups = GameGroup::find()->all();
        return ArrayHelper::map($groups, 'id', function($obj) {
            return [
                'data-method' => ArrayHelper::map($obj->getMethods(), 'id', 'title'),
                'data-version' => ArrayHelper::map($obj->getVersions(), 'id', 'title'),
                'data-package' => ArrayHelper::map($obj->packages, 'id', 'title'),
            ];
        });

    }

}
