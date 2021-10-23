<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Game;
use backend\models\GameGroup;
use backend\models\GameCategory;
use backend\models\GameSetting;
use backend\models\GameCategoryItem;
use yii\helpers\ArrayHelper;

class CreateGameForm extends Model
{
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
    public $price1;
    public $price2;
    public $price3;
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
    public $min_quantity;

    public function rules()
    {
        return [
            [['title', 'content', 'unit_name', 'pack'], 'required'],
            ['status', 'default', 'value' => Game::STATUS_INVISIBLE],
            [['image_id', 'excerpt', 'units', 'reseller_price'], 'safe'],
            ['pack', 'default', 'value' => 1],
            ['pack', 'number'],
            ['pin', 'default', 'value' => Game::UNPIN],
            ['soldout', 'default', 'value' => 0],
            ['original_price', 'trim'],
            ['original_price', 'double'],
            [['price1', 'price2', 'price3'], 'double'],
            [['meta_title', 'meta_keyword', 'meta_description', 'promotion_info', 'event_info'], 'trim'],
            [['average_speed', 'number_supplier', 'remark', 'price_remark', 'google_ads', 'categories'], 'safe'],
            [['hot_deal', 'new_trending', 'top_grossing', 'back_to_stock'], 'safe'],
            [['group_id', 'method', 'package', 'version'], 'safe'],
            ['group_id', 'validateGroup'],
            ['min_quantity', 'number']
        ];
    }

    public function validateGroup($attribute, $params) 
    {   
        if (!$this->group_id) return;
        if (!$this->method || !$this->version || !$this->package) {
            $this->addError($attribute, 'Những thông số đi kèm không được bỏ trống');
            return;
        }
        if (Game::find()->where([
            'group_id' => $this->group_id,
            'method' => $this->method,
            'version' => $this->version,
            'package' => $this->package,
        ])->exists()) {
            $this->addError($attribute, 'Những thông số này đã được dùng cho 1 game khác.');
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
            'price1' => 'Giá nhà cung cấp 1',
            'price2' => 'Giá nhà cung cấp 2',
            'price3' => 'Giá nhà cung cấp 3',
            'average_speed' => 'Tốc độ xử lý (phút)',
            'number_supplier' => 'Số nhà cung cấp',
            'remark' => 'Remark',
            'price_remark' => 'Remark',
            'google_ads' => 'Google Ads',
            'categories' => 'Danh mục game',
            'min_quantity' => 'Số gói nhỏ nhất khi đặt hàng'
        ];
    }

    public function save()
    {
        $transaction = Yii::$app->db->beginTransaction();
        $post = $this->getGame();
        try {
            $post->save();
            $newId = $post->id;

            // categories
            $categories = array_filter((array)$this->categories);
            foreach ($categories as $categoryId) {
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
        $post = new Game();
        $post->title = $this->title;
        $post->excerpt = $this->excerpt;
        $post->content = $this->content;
        $post->unit_name = $this->unit_name;
        $post->status = Game::STATUS_INVISIBLE;
        $post->image_id = $this->image_id;
        $post->reseller_price = $this->reseller_price;
        $post->original_price = $this->original_price;
        $post->pack = $this->pack;
        $post->pin = $this->pin;
        $post->soldout = $this->soldout;
        $post->price1 = $this->price1;
        $post->price2 = $this->price2;
        $post->price3 = $this->price3;
        $post->promotion_info = $this->promotion_info;
        $post->event_info = $this->event_info;
        $post->average_speed = $this->average_speed;
        $post->number_supplier = $this->number_supplier;
        $post->remark = $this->remark;
        $post->price_remark = $this->price_remark;
        $post->google_ads = $this->google_ads;
        $post->meta_title = $this->meta_title;
        $post->meta_keyword = $this->meta_keyword;
        $post->meta_description = $this->meta_description;
        $post->hot_deal = $this->hot_deal;
        $post->new_trending = $this->new_trending;
        $post->top_grossing = $this->top_grossing;
        $post->back_to_stock = $this->back_to_stock;
        $post->group_id = $this->group_id;
        $post->method = $this->method;
        $post->version = $this->version;
        $post->package = $this->package;
        $post->min_quantity = $this->min_quantity;
        return $post;
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
