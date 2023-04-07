<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Game;
use backend\models\GameCategory;
use backend\models\GameCategoryItem;
use backend\models\GameGroup;
use backend\models\GamePackage;
use backend\models\GameMethod;
use backend\models\GameVersion;
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
    public $expected_profit;
    public $canUpdateProfit = false;

    protected $_group;
    protected $_method;
    protected $_package;
    protected $_version;

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
            ['min_quantity', 'number'],
            ['expected_profit', 'safe'],
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
            'min_quantity' => 'Số gói nhỏ nhất khi đặt hàng',
            'group_id' => 'Nhóm game',
            'method' => 'Phương thức nạp',
            'version' => 'Phiên bản nạp',
            'package' => 'Loại gói'
        ];
    }

    public function save()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Create group
            $method = $this->getMethod();
            if (!$method) {
                $method = new GameMethod();
                $title = $this->method;
                $lastPos = strrpos($this->method, "(");
                if ($lastPos !== false) {
                    $match = substr($this->method, $lastPos + 1, strlen($this->method) - $lastPos - 2);
                    $title = substr($title, 0, $lastPos);
                    $weights = explode('|', $match);
                    foreach ($weights as $value) {
                        $pairs = explode(':', $value);
                        $key = strtolower(trim(array_shift($pairs)));
                        if (in_array($key, ['speed', 'safe', 'price'])) {
                            $method->$key = array_shift($pairs);
                        }
                    }
                }
                
                $method->title = trim($title);
                $method->description = trim($title);
                $method->save();
            }
            $version = $this->getVersion();
            if (!$version) {
                $version = new GameVersion();
                $version->title = $this->version;
                $version->save();
            }

            $group = $this->getGroup();
            if (!$group) {
                $group = new GameGroup();
                $group->title = $this->group_id;
                $group->method = $method->id;
                $group->version = $version->id;
            } else {
                $group->method = in_array($method->id, explode(',', $group->method)) ? $group->method : sprintf('%s,%s', $group->method, $method->id);
                $group->version = in_array($version->id, explode(',', $group->version)) ? $group->version : sprintf('%s,%s', $group->version, $version->id);
            }
            $group->save();


            $package = $this->getPackages();
            if (!$package) {
                $package = new GamePackage();
                $package->title = $this->package;
                $package->group_id = $group->id;
                $package->save();
            }

            $game = new Game();
            $game->title = $this->title;
            $game->excerpt = $this->excerpt;
            $game->content = $this->content;
            $game->unit_name = $this->unit_name;
            $game->status = Game::STATUS_INVISIBLE;
            $game->image_id = $this->image_id;
            $game->reseller_price = $this->reseller_price;
            $game->original_price = $this->original_price;
            $game->pack = $this->pack;
            $game->pin = $this->pin;
            $game->soldout = $this->soldout;
            $game->price1 = $this->price1;
            $game->price2 = $this->price2;
            $game->price3 = $this->price3;
            $game->promotion_info = $this->promotion_info;
            $game->event_info = $this->event_info;
            $game->average_speed = $this->average_speed;
            $game->number_supplier = $this->number_supplier;
            $game->remark = $this->remark;
            $game->price_remark = $this->price_remark;
            $game->google_ads = $this->google_ads;
            $game->meta_title = $this->meta_title;
            $game->meta_keyword = $this->meta_keyword;
            $game->meta_description = $this->meta_description;
            $game->hot_deal = $this->hot_deal;
            $game->new_trending = $this->new_trending;
            $game->top_grossing = $this->top_grossing;
            $game->back_to_stock = $this->back_to_stock;
            $game->group_id = $group->id;
            $game->method = $method->id;
            $game->method_title = $method->title;
            $game->version = $version->id;
            $game->version_title = $version->title;
            $game->package = $package->id;
            $game->package_title = $package->title;
            $game->min_quantity = $this->min_quantity;
            if ($this->canUpdateProfit) {
                $game->expected_profit = $this->expected_profit;
            }
            $game->save();
            $newId = $game->id;

            // categories
            $categories = array_filter((array)$this->categories);
            foreach ($categories as $categoryId) {
                $postCategory = new GameCategoryItem();
                $postCategory->game_id = $newId;
                $postCategory->category_id = $categoryId;
                $postCategory->save();
            }

            $transaction->commit();
            $this->group_id = $group->id;
            $this->method = $method->id;
            $this->version = $version->id;
            $this->package = $package->id;
            return $newId;
        } catch (Exception $e) {
            $transaction->rollBack();                
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
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

    protected function getGroup()
    {
        if (!$this->_group) {
            $this->_group = GameGroup::findOne($this->group_id);
        }
        return $this->_group;
    }

    protected function getMethod()
    {
        if (!$this->_method) {
            $this->_method = GameMethod::findOne($this->method);
        }
        return $this->_method;
    }

    protected function getVersion()
    {
        if (!$this->_version) {
            $this->_version = GameVersion::findOne($this->version);
        }
        return $this->_version;
    }

    protected function getPackages()
    {
        if (!$this->_package) {
            $this->_package = GamePackage::findOne($this->package);
        }
        return $this->_package;
    }

    public function fetchGroups()
    {
        return ArrayHelper::map(GameGroup::find()->all(), 'id', 'title');
    }

    public function fetchMethods()
    {
        return ArrayHelper::map(GameMethod::find()->all(), 'id', function($obj) {
            return sprintf("%s(Speed:%s|Safe:%s|Price:%s)", $obj->title, (int)$obj->speed, (int)$obj->safe, (int)$obj->price);
        });
    }

    public function fetchVersions()
    {
        return ArrayHelper::map(GameVersion::find()->all(), 'id', 'title');
    }

    public function fetchPackages()
    {
        return ArrayHelper::map(GamePackage::find()->all(), 'id', 'title');
    }
}
