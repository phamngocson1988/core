<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Game;

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

    protected $_game;

    public function rules()
    {
        return [
            [['id', 'title', 'content'], 'required'],
            ['status', 'default', 'value' => Game::STATUS_VISIBLE],
            ['id', 'validateGame'],
            [['excerpt', 'image_id', 'meta_title', 'meta_keyword', 'meta_description'], 'safe']
        ];
    }

    public function attributeLabels() { 

        return  [
            'id' => Yii::t('app', 'id'),
            'title' => Yii::t('app', 'title'),
            'content' => Yii::t('app', 'description'),
            'status' => Yii::t('app', 'status'),
            'excerpt' => Yii::t('app', 'excerpt'),
            'image_id' => Yii::t('app', 'image'),
            'meta_title' => Yii::t('app', 'meta_title'),
            'meta_keyword' => Yii::t('app', 'meta_keyword'),
            'meta_description' => Yii::t('app', 'meta_description'),
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $game = $this->getGame();
                $game->title = $this->title;
                $game->content = $this->content;
                $game->excerpt = $this->excerpt;
                $game->image_id = $this->image_id;
                $game->meta_title = $this->meta_title;
                $game->meta_keyword = $this->meta_keyword;
                $game->meta_description = $this->meta_description;
                $game->status = $this->status;
                $game->save();
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

    public function getStatusList()
    {
        return Game::getStatusList();
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
            $this->_game = Game::findOne($this->id);
        }

        return $this->_game;
    }

    public function setGame($game) 
    {
        if ($game instanceof Game) {
            $this->_game = $game;
            $this->id = $game->id;
        }
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
    }
}
