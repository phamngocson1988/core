<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Game;

class DeleteGameForm extends Model
{
    public $id;

    private $_game;

	public function rules()
    {
        return [
            ['id', 'required'],
            ['id', 'validateGame'],
        ];
    }

    public function delete()
    {
    	if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            $game = $this->getGame();
            try {
            	$result = $game->delete();
                $transaction->commit();
                return $result;
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

    public function validateGame($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $game = $this->getGame();
            if (!$game) {
                $this->addError($attribute, Yii::t('app', 'invalid_game'));
            }
        }
    }

    protected function getGame()
    {
        if ($this->_game === null) {
            $this->_game = Game::findOne($this->id);
        }

        return $this->_game;
    }
}
