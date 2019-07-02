<?php
namespace common\models\promotions;

use Yii;
use yii\helpers\ArrayHelper;
use common\models\Game;

/**
 * SpecifiedGamesRule model
 */
class SpecifiedGamesRule extends PromotionRuleAbstract implements PromotionRuleInterface
{
    public $games;

    public $object = self::EFFECT_GAME;

    protected $_all_games;

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['games'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'games' => 'Game',
        ];
    }

    public function render($form, $attr, $options = [])
    {
        if (!$this->isSafeAttribute($attr)) return '';
        $allGames = $this->loadAllGames();
        return $form->field($this, $attr, $options)->widget(\kartik\select2\Select2::classname(), [
            'data' => ArrayHelper::map($allGames, 'id', 'title'),
            'options' => ['class' => 'form-control', 'multiple' => 'true'],
        ]);
    }

    public function isValid($gameId)
    {
        if (!$this->games) return false;
        return in_array($gameId, $this->games);
    }

    protected function loadAllGames()
    {
        if (!$this->_all_games) {
            $this->_all_games = Game::find()->select(['id', 'title'])->where(['IN', 'status', [Game::STATUS_VISIBLE, Game::STATUS_INVISIBLE]])->all();
        }
        return $this->_all_games;
    }
}