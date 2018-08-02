<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Department model
 *
 * @property integer $id
 * @property string $name
 * @property string $branch
 * @property string $phone
 * @property integer $parent_id
 */
class Department extends ActiveRecord
{
    const BRANCH_HOCHIMINH = 'hochiminh';
    const BRANCH_PHANRANG = 'phanrang';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%department}}';
    }

	public function getParent()
    {
        return $this->hasOne(self::className(), ['id' => 'parent_id']);
    }

    public function getParentName()
    {
        $obj = $this->parent;
        if ($obj) {
            return $obj->name;
        }
    }

    public static function getBranches()
    {
        return [
            self::BRANCH_HOCHIMINH => 'Hồ Chí Minh',
            self::BRANCH_PHANRANG => 'Phan Rang',            
        ];
    }

    public function getBranchLabel()
    {
        $labels = self::getBranches();
        return ArrayHelper::getValue($labels, $this->branch);
    }
}
