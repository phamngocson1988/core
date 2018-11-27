<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Game;
use common\models\GameImage;
use yii\helpers\ArrayHelper;

class EditGameForm extends Model
{
    public $id;
    public $title;
    public $content;
    public $excerpt;
    public $image_id;
    public $meta_title;
    public $meta_keyword;
    public $meta_description;
    public $status = Game::STATUS_VISIBLE;
    public $gallery = [];
    public $products = [];

    private $_game;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'title', 'content'], 'required'],
            ['id', 'validateGame'],
            [['excerpt', 'image_id', 'meta_title', 'meta_keyword', 'meta_description', 'gallery'], 'safe'],
            ['products', 'validateProducts'],
            ['status', 'default', 'value' => Game::STATUS_VISIBLE],
        ];
    }

    public function attributeLabels() { 

        return  [
            'title' => Yii::t('app', 'title'),
            'content' => Yii::t('app', 'description'),
            'status' => Yii::t('app', 'status'),
            'products' => Yii::t('app', 'products'),
            'excerpt' => Yii::t('app', 'excerpt'),
            'image_id' => Yii::t('app', 'image'),
            'meta_title' => Yii::t('app', 'meta_title'),
            'meta_keyword' => Yii::t('app', 'meta_keyword'),
            'meta_description' => Yii::t('app', 'meta_description'),
            'gallery' => Yii::t('app', 'gallery'),
        ];
    }

    public function validateProducts($attribute, $params)
    {
        foreach ($this->products as $key => $data) {
            $product = $this->bindProduct($data);
            if (!$product->validate()) {
                foreach ($product->getErrors() as $errKey => $errors) {
                    $this->addError("products[$key][$errKey]", reset($errors));
                }
            }
        }
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

    protected function bindProduct($data)
    {
        if (ArrayHelper::getValue($data, 'id')) {
            return new EditProductForm($data);
        } else {
            $data = array_filter($data);
            $game = $this->getGame();
            $product = new CreateProductForm($data);
            $product->game_id = $game->id;
            return $product;
        }
    }

    public function save()
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $now = date('Y-m-d H:i:s');
                $game = $this->getGame();
                $game->title = $this->title;
                $game->content = $this->content;
                $game->excerpt = $this->excerpt;
                $game->image_id = $this->image_id;
                $game->meta_title = $this->meta_title;
                $game->meta_keyword = $this->meta_keyword;
                $game->meta_description = $this->meta_description;
                $game->updated_at = $now;
                $game->status = $this->status;
                $result = $game->save();

                if ($result) {
                    $this->updateGallery();
                    $this->updateProducts();
                }

                $transaction->commit();
                return $game;
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

    public function getGame()
    {
        if ($this->_game === null) {
            $this->_game = Game::findOne($this->id);
        }

        return $this->_game;
    }

    public function loadData($id)
    {
        $this->id = $id;
        $game = $this->getGame();
        $this->title = $game->title;
        $this->content = $game->content;
        $this->excerpt = $game->excerpt;
        $this->image_id = $game->image_id;
        $this->meta_title = $game->meta_title;
        $this->meta_keyword = $game->meta_keyword;
        $this->meta_description = $game->meta_description;
        $this->status = $game->status;
        $gallery = $game->gallery;
        $this->gallery = ArrayHelper::getColumn($gallery, 'id');

        foreach ($game->products as $product) {
            $data = [];
            $data['id'] = $product->id;
            $data['game_id'] = $product->game_id;
            $data['title'] = $product->title;
            $data['price'] = $product->price;
            $data['gems'] = $product->gems;
            $this->products[] = $data;
        }
    }

    public function hasImage()
    {
        $game = $this->getGame();
        return $game->image;
    }
    public function getImageUrl($size)
    {
        $game = $this->getGame();
        return $game->getImageUrl($size, '');
    }

    public function getGallery()
    {
        $gallery = (array)$this->gallery;
        $gallery = array_filter($gallery);
        $gallery = array_unique($gallery);
        return $gallery;
    }

    public function getGalleryImages()
    {
        $game = $this->getGame();
        return $game->gallery;
    }

    public function getStatusList()
    {
        return Game::getStatusList();
    }

    //============== Update relevants ==============
    protected function updateProducts()
    {
        $game = $this->getGame();

        // Remove products removed from the game
        $oldProducts = $game->products;
        $formProductIds = ArrayHelper::getColumn($this->products, 'id');
        $formProductIds = array_filter($formProductIds);

        foreach ($oldProducts as $oldProduct) {
            if (!in_array($oldProduct->id, $formProductIds)) {
                $oldProduct->delete();
            }
        }

        // Create new products added to the game
        foreach ($this->products as $data) {
            $product = $this->bindProduct($data);
            $product->save();
        }
    }

    protected function updateGallery()
    {
        $game = $this->getGame();

        $oldImages = GameImage::findAll(['game_id' => $game->id]);
        foreach ($oldImages as $oldImage) {
            $oldImage->delete();
        }

        $newImages = $this->getGallery(); // list ids of new images
        foreach ($newImages as $newImage) {
            $gameImage = new GameImage();
            $gameImage->game_id = $game->id;
            $gameImage->image_id = $newImage;
            $gameImage->save();
        }
    }
}
