<?php
namespace backend\models;

use Yii;

class Game extends \common\models\Game
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_EDIT = 'edit';

    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['title', 'excerpt', 'content', 'unit_name', 'status', 'image_id', 'price', 'original_price', 'pack', 'pin'],
            self::SCENARIO_EDIT => ['id', 'excerpt', 'title', 'content', 'unit_name', 'status', 'image_id', 'price', 'original_price', 'pack', 'pin'],
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
            'price' => 'Giá bán (Kcoin) / gói game',
            'original_price' => 'Giá gốc (Kcoin) / gói game',
            'pack' => 'Số đơn vị game trong gói',
            'pin' => 'Ưu tiên hiển thị'
        ];
    }

    public function rules()
    {
        return [
            ['id', 'required', 'on' => self::SCENARIO_EDIT],
            [['title', 'content', 'unit_name', 'price', 'pack'], 'required'],
            ['status', 'default', 'value' => self::STATUS_VISIBLE],
            [['image_id', 'excerpt'], 'safe'],
            ['pack', 'default', 'value' => 1],
            ['pin', 'default', 'value' => self::UNPIN],
            ['original_price', 'trim']
        ];
    }

	public static function deleteAll($condition = null, $params = [])
    {
        return static::updateAll(['status' => self::STATUS_DELETE], $condition);
    }
}