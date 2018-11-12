<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Game;
use common\models\GameImage;
use yii\helpers\ArrayHelper;

class CreateGameForm extends Model
{
    public $title;
    public $slug;
    public $content;
    public $excerpt;
    public $image_id;
    public $meta_title;
    public $meta_keyword;
    public $meta_description;
    public $status = Game::STATUS_VISIBLE;
    public $gallery = [];
    public $packages = []; // CreateProductForm[]

    public function init()
    {
        parent::init();
        foreach ($this->packages as $key => $data) {
            $this->packages[$key] = new CreateProductForm($data);
        }
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content', 'slug'], 'required'],
            ['status', 'default', 'value' => Game::STATUS_VISIBLE],
            ['packages', 'validatePackages'],
            [['excerpt', 'image_id', 'meta_title', 'meta_keyword', 'meta_description', 'gallery'], 'safe']
        ];
    }

    public function validatePackages($attribute, $params)
    {
        $flash = true;
        foreach ($this->packages as $key => $product) {
            $flash = $flash && $product->validate();
        }
        if (!$flash) {
            $this->addError('*', Yii::t('package_error'));
        }
    }

    public function save()
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $now = date('Y-m-d H:i:s');
                $game = new Game();
                $game->title = $this->title;
                $game->slug = $this->slug;
                $game->content = $this->content;
                $game->excerpt = $this->excerpt;
                $game->image_id = $this->image_id;
                $game->meta_title = $this->meta_title;
                $game->meta_keyword = $this->meta_keyword;
                $game->meta_description = $this->meta_description;
                $game->created_by = Yii::$app->user->id;
                $game->created_at = $now;
                $game->updated_at = $now;
                $game->status = $this->status;
                $game->save();
                $newId = $game->id;

                // Galleries
                if ($newId) {
                    foreach ($this->packages as $product) {
                        $product->game_id = $newId;
                        $product->save();
                    }
                    foreach ($this->getGallery() as $imageId) {
                        $productImage = new GameImage();
                        $productImage->image_id = $imageId;
                        $productImage->game_id = $newId;
                        $productImage->save();
                    }    
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

    public function getGallery()
    {
        $gallery = (array)$this->gallery;
        return array_filter($gallery);
    }
}
