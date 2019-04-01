<?php
namespace backend\forms;

use Yii;
use common\models\OrderItems;
use common\models\Game;
use common\models\Product;
use common\models\File;
use yii\helpers\ArrayHelper;

class EditOrderItemForm extends OrderItems
{
    const SCENARIO_VERIFYING = 'verifying';
    const SCENARIO_PENDING = 'pending';
    const SCENARIO_PROCESSING = 'processing';

    public function scenarios()
    {
        return [
            self::SCENARIO_VERIFYING => ['game_id', 'product_id', 'quantity', 'username', 'password', 'platform', 'login_method', 'character_name', 'recover_code', 'server', 'note'],
            self::SCENARIO_PENDING => ['username', 'password', 'platform', 'login_method', 'character_name', 'recover_code', 'server', 'note', 'image_before_payment', 'image_after_payment'],
            self::SCENARIO_PROCESSING => ['image_before_payment', 'image_after_payment'],
        ];
    }

    public function rules()
    {
        return [
            [['game_id', 'product_id', 'quantity'], 'required', 'on' => self::SCENARIO_VERIFYING],
            ['game_id', 'validateGame', 'on' => self::SCENARIO_VERIFYING],
            ['product_id', 'validateProduct', 'on' => self::SCENARIO_VERIFYING],
            [['username', 'password', 'platform', 'login_method', 'character_name'], 'required'],
            [['recover_code', 'server', 'note'], 'trim'],
            [['image_before_payment', 'image_after_payment'], 'safe']
        ];
    }

    public function validateGame($attribute, $params)
    {
        if (!$this->game) {
            $this->addError($attribute, 'Game không tồn tại');
        }
    }

    public function validateProduct($attribute, $params)
    {
        if (!$this->product) {
            $this->addError($attribute, 'Gói game không tồn tại');
        }
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        switch ($this->scenario) {
            case self::SCENARIO_VERIFYING:
                $this->item_title = sprintf("%s - %s", $this->game->title, $this->product->title);
                $this->type = self::TYPE_PRODUCT;
                $this->price = $this->product->price;
                $this->total = $this->product->price * $this->quantity;
                $this->unit_name = $this->game->unit_name;
                $this->unit = $this->product->unit;
                $this->total_unit = $this->product->unit * $this->quantity;
                break;
            default:
                # code...
                break;
        }
        return true;
    }

    public function getImageBefore()
    {
        if (!$this->image_before_payment) return '';
        $file = File::findOne($this->image_before_payment);
        if (!$file) return '';
        return $file->getUrl();
    }

    public function getImageAfter()
    {
        if (!$this->image_after_payment) return '';
        $file = File::findOne($this->image_after_payment);
        if (!$file) return '';
        return $file->getUrl();
    }
}