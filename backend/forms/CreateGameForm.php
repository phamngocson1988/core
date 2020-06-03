<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Game;
use backend\models\GameCategory;
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
    public $meta_title;
    public $meta_keyword;
    public $meta_description;
    public $hot_deal;
    public $new_trending;
    public $top_grossing;
    public $back_to_stock;
    public $categories;

    public function rules()
    {
        return [
            [['title', 'content', 'unit_name', 'pack'], 'required'],
            ['status', 'default', 'value' => Game::STATUS_VISIBLE],
            [['image_id', 'excerpt', 'units', 'reseller_price'], 'safe'],
            ['pack', 'default', 'value' => 1],
            ['pin', 'default', 'value' => Game::UNPIN],
            ['soldout', 'default', 'value' => 0],
            [['original_price'], 'trim'],
            [['price1', 'price2', 'price3'], 'safe'],
            [['meta_title', 'meta_keyword', 'meta_description'], 'trim'],
            [['average_speed', 'number_supplier', 'remark', 'price_remark', 'google_ads', 'categories'], 'safe'],
            [['hot_deal', 'new_trending', 'top_grossing', 'back_to_stock'], 'safe']
        ];
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
        $post->status = $this->status;
        $post->image_id = $this->image_id;
        $post->reseller_price = $this->reseller_price;
        $post->original_price = $this->original_price;
        $post->pack = $this->pack;
        $post->pin = $this->pin;
        $post->soldout = $this->soldout;
        $post->price1 = $this->price1;
        $post->price2 = $this->price2;
        $post->price3 = $this->price3;
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

}
