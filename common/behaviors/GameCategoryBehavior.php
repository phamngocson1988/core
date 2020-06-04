<?php
namespace common\behaviors;

use Yii;
use yii\behaviors\AttributeBehavior;
use common\models\GameCategory;
use common\models\GameCategoryItem;

class GameCategoryBehavior extends AttributeBehavior
{
    public function getCategories() 
    {
        $owner = $this->owner; //Game
        return $owner->hasMany(GameCategory::className(), ['id' => 'category_id'])
        ->viaTable(GameCategoryItem::tableName(), ['game_id' => 'id']);
    }

    public function hasCategory()
    {
        return $this->getCategories()->exists();
    }
}
