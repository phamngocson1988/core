<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * File model
 *
 * @property integer $id
 * @property string $name
 * @property string $extension
 * @property string $size
 * @property integer $created_at
 * @property integer $created_by
 */
class File extends ActiveRecord
{
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%file}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => date('Y-m-d H:i:s')
            ],
        ];
    }

    public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getExtension()
	{
		return $this->extension;
	}

	public function getSize($format = false)
	{
		if ($format === true) {
			return number_format($this->size);
		}
		return (int)$this->size;
	}

	public function getUrl()
	{
		return Yii::$app->file->get($this);
	}
}
