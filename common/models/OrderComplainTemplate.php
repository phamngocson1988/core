<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\Order;

/**
 * OrderComplainTemplate model
 */
class OrderComplainTemplate extends ActiveRecord
{
	const SCENARIO_CREATE = 'create';
    const SCENARIO_EDIT = 'edit';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_complain_template}}';
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['content'],
            self::SCENARIO_EDIT => ['id', 'content'],
        ];
    }

    public function rules()
    {
    	return [
    		[['content'], 'required', 'on' => self::SCENARIO_CREATE],
    		[['id', 'content'], 'required', 'on' => self::SCENARIO_EDIT],
    	];
    }
}
