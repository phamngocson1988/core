<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Department model
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property integer $parent_id
 */
class Department extends ActiveRecord
{
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

    public function getDepartmentType()
    {
    	return (!$this->parent_id) ? Yii::t('app', 'type_branch') : Yii::t('app', 'type_department');
    }
}
