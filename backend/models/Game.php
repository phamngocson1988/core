<?php
namespace backend\models;

use Yii;

class Game extends \common\models\Game
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_EDIT = 'edit';

    public $units;
    
    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['title', 'short_title', 'excerpt', 'content', 'unit_name', 'status', 'image_id', 'price', 'reseller_price', 'original_price', 'pack', 'pin', 'units'],
            self::SCENARIO_EDIT => ['id', 'excerpt', 'title', 'short_title', 'content', 'unit_name', 'status', 'image_id', 'price', 'reseller_price', 'original_price', 'pack', 'pin', 'units'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('app', 'title'),
            'short_title' => 'Tên viết tắt',
            'excerpt' => Yii::t('app', 'excerpt'),
            'content' => Yii::t('app', 'content'),
            'unit_name' => 'Tên đơn vị game',
            'status' => 'Trạng thái sản phẩm',
            'image_id' => 'Hình ảnh',
            'price' => 'Giá bán (Kcoin) / gói game',
            'reseller_price' => 'Giá dành cho đại lý',
            'original_price' => 'Giá gốc (Kcoin) / gói game',
            'pack' => 'Số đơn vị game trong gói',
            'pin' => 'Ưu tiên hiển thị',
        ];
    }

    public function rules()
    {
        return [
            ['id', 'required', 'on' => self::SCENARIO_EDIT],
            [['title', 'content', 'unit_name', 'price', 'pack'], 'required'],
            ['status', 'default', 'value' => self::STATUS_VISIBLE],
            [['image_id', 'excerpt', 'units', 'reseller_price'], 'safe'],
            ['pack', 'default', 'value' => 1],
            ['pin', 'default', 'value' => self::UNPIN],
            [['original_price', 'short_title'], 'trim'],
        ];
    }

	public static function deleteAll($condition = null, $params = [])
    {
        return static::updateAll(['status' => self::STATUS_DELETE], $condition);
    }
}