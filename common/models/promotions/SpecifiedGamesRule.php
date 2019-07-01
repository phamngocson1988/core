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

    public function isValid($params)
    {
        if (!$this->games) return false;
        $gameIds = ArrayHelper::getValue($params, 'game_id');
        if (!$gameIds) return false;
        if (!is_array($gameIds)) $gameIds = (array)$gameIds;
        return !empty(array_intersect($gameIds, $this->games));
    }

    protected function loadAllGames()
    {
        if (!$this->_all_games) {
            $this->_all_games = Game::find()->select(['id', 'title'])->where(['IN', 'status', [Game::STATUS_VISIBLE, Game::STATUS_INVISIBLE]])->all();
        }
        return $this->_all_games;
    }
}