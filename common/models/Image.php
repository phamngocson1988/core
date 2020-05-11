<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\components\helpers\FileHelper;
use yii\imagine\Image as ImageHandler;
use yii\helpers\ArrayHelper;

/**
 * Image model
 *
 * @property integer $id
 * @property string $name
 * @property string $extension
 * @property string $size
 * @property integer $created_at
 * @property integer $created_by
 */
class Image extends ActiveRecord
{
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%image}}';
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

	public function getUrl($size = null)
	{
		return Yii::$app->image->get($this, $size);
	}

}
