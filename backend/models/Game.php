<?php
namespace backend\models;

use Yii;

class Game extends \common\models\Game
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_EDIT = 'edit';
    const SCENARIO_UPDATE_PRICE = 'price';

    public $units = [];
    
    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['title', 'excerpt', 'content', 'unit_name', 'status', 'image_id', 'reseller_price', 'original_price', 
            'pack', 'pin', 'soldout', 'price1', 'price2', 'price3', 'meta_title', 'meta_keyword', 'meta_description'],
            self::SCENARIO_EDIT => ['id', 'excerpt', 'title', 'content', 'unit_name', 'status', 'image_id', 'reseller_price', 'original_price', 'pack', 'pin', 'soldout', 'meta_title', 'meta_keyword', 'meta_description'],
            self::SCENARIO_UPDATE_PRICE => ['price1', 'price2', 'price3'],
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
        ];
    }

    public function rules()
    {
        return [
            ['id', 'required', 'on' => self::SCENARIO_EDIT],
            [['title', 'sku', 'content', 'unit_name', 'price', 'pack'], 'required'],
            ['status', 'default', 'value' => self::STATUS_VISIBLE],
            [['image_id', 'excerpt', 'units', 'reseller_price'], 'safe'],
            ['pack', 'default', 'value' => 1],
            ['pin', 'default', 'value' => self::UNPIN],
            ['soldout', 'default', 'value' => 0],
            [['original_price'], 'trim'],
            [['price1', 'price2', 'price3'], 'safe'],
            [['meta_title', 'meta_keyword', 'meta_description'], 'trim']
        ];
    }

	public static function deleteAll($condition = null, $params = [])
    {
        return static::updateAll(['status' => self::STATUS_DELETE], $condition);
    }
}