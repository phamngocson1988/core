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
    public $slug;
    public $content;
    public $excerpt;
    public $image_id;
    public $meta_title;
    public $meta_keyword;
    public $meta_description;
    public $status = Game::STATUS_VISIBLE;
    public $gallery = [];

    private $_game;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'title', 'content', 'slug'], 'required'],
            ['id', 'validateGame'],
            ['status', 'default', 'value' => Game::STATUS_VISIBLE],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_DEFAULT] = ['id', 'title', 'slug', 'content', 'excerpt', 'image_id', 'status', 'gallery', 'meta_title', 'meta_keyword', 'meta_description'];
        return $scenarios;
    }

    public function save()
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $now = date('Y-m-d H:i:s');
                $game = $this->getGame();
                $game->title = $this->title;
                $game->slug = $this->slug;
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
                    // Delete old images
                    $oldImages = $game->gallery;
                    $oldImageIds = [];
                    $newImages = $this->getGallery(); // list ids of new images
                    foreach ($oldImages as $oldImage) {
                        $oldImageIds[] = $oldImage->id;
                        if (!in_array($oldImage->id, $newImages)) {
                            $oldImage->delete();
                        }
                    }
                    $newImageIds = array_diff($newImages, $oldImageIds);
                    foreach ($newImageIds as $imageId) {
                        $gameImage = new GameImage();
                        $gameImage->image_id = $imageId;
                        $gameImage->game_id = $this->id;
                        $gameImage->save();
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
        $this->slug = $game->slug;
        $this->content = $game->content;
        $this->excerpt = $game->excerpt;
        $this->image_id = $game->image_id;
        $this->meta_title = $game->meta_title;
        $this->meta_keyword = $game->meta_keyword;
        $this->meta_description = $game->meta_description;
        $this->status = $game->status;
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
        return array_filter($gallery);
    }

    public function getGalleryImages()
    {
        $game = $this->getGame();
        return $game->gallery;
    }
}
