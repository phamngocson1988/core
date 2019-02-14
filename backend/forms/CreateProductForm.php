<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Product;
use common\models\Game;

class CreateProductForm extends Model
{
    public $title;
    public $game_id;
    public $image_id;
    public $price;
    public $unit;
    public $status = Product::STATUS_VISIBLE;

    protected $_game;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'game_id', 'price', 'unit'], 'required'],
            ['status', 'default', 'value' => Product::STATUS_VISIBLE],
            [['image_id'], 'safe'],
            ['game_id', 'validateGame'],
        ];
    }

    public function attributeLabels() 
    { 
        $game = $this->getGame();
        return  [
            'title' => Yii::t('app', 'title'),
            'game_id' => Yii::t('app', 'game_id'),
            'image_id' => Yii::t('app', 'image'),
            'price' => Yii::t('app', 'price'),
            'unit' => $game->unit_name,
            'status' => Yii::t('app', 'status'),
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $product = new Product();
                $product->title = $this->title;
                $product->game_id = $this->game_id;
                $product->image_id = $this->image_id;
                $product->price = $this->price;
                $product->unit = $this->unit;
                $product->created_by = Yii::$app->user->id;
                $product->status = $this->status;
                if (!$product->save()) {
                    throw new Exception("Error Processing Request", 1);
                }
                
                $transaction->commit();
                return $product;
            } catch (Exception $e) {
                $transaction->rollBack();                
                throw $e;
            } catch (\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
        }
        return false;
    }

    public function getStatusList()
    {
        return Product::getStatusList();
    }

    public function validateGame($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $game = $this->getGame();
            if (!$game) {
                $this->addError($attribute, 'Invalid game.');
            }
        }
    }

    public function getGame()
    {
        if ($this->_game === null) {
            $this->_game = Game::findOne($this->game_id);
        }

        return $this->_game;
    }
}
