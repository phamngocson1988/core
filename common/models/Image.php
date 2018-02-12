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
		if (!$size) {
			$format = "%s/%s.%s";
			return sprintf($format, $this->getAbsoluteUrl(), $this->getName(), $this->getExtension());
		} else {
			$format = "%s/%s/%s.%s";
			return sprintf($format, $this->getAbsoluteUrl(), $size, $this->getName(), $this->getExtension());
		}
	}

	public function getPath($size = null) {
		if (!$size) {
			$format = "%s/%s.%s";
			return sprintf($format, $this->getAbsolutePath(), $this->getName(), $this->getExtension());
		} else {
			$format = "%s/%s/%s.%s";
			return sprintf($format, $this->getAbsolutePath(), $size, $this->getName(), $this->getExtension());
		}
	}

	public function getRootUrl()
	{
		return Yii::$app->params['image_url'];
	}

	public function getRootPath()
	{
		return Yii::$app->params['image_path'];
	}

	public function getRelativePath()
	{
		return FileHelper::getRelativePath($this->getId());
	}

	public function getAbsolutePath()
	{
		return sprintf("%s/%s", $this->getRootPath(), $this->getRelativePath());
	}

	public function getAbsoluteUrl()
	{
		return sprintf("%s/%s", $this->getRootUrl(), $this->getRelativePath());	
	}

	public function saveThumb($thumbSize)
    {
        $sizes = explode("x", $thumbSize);
        if (count($sizes) < 2) {
            return;
        }

        $filePath = $this->getPath();
        $thumbPath = $this->getPath($thumbSize);
        $thumbDir = dirname($thumbPath);
        FileHelper::createDirectory($thumbDir);
        $thumbWidth = ArrayHelper::getValue($sizes, 0);
        $thumbHeight = ArrayHelper::getValue($sizes, 1);
        $thumb = ImageHandler::thumbnail($filePath, $thumbWidth, $thumbHeight);
        $thumb->save($thumbPath);
    }

    public function generateHtml($options = [], $size = null, $hiddenOptions = null) 
    {
    	$options['src'] = $this->getUrl($size);
    	$imgAttr = [];
    	foreach ($options as $key => $value) {
    		$imgAttr[] = "$key='$value'";
    	}
    	$imgElement = sprintf("<img %s />", implode(" ", $imgAttr));

    	$hiddenElement = "";
    	if ($hiddenOptions !== null) {
    		$hiddenAttr = [];
	    	foreach ((array)$hiddenOptions as $key => $value) {
	    		$hiddenAttr[] = "$key='$value'";
	    	}
    		$hiddenElement = sprintf("<input type='hidden' %s />", implode(" ", $hiddenAttr));
    	}

    	return $imgElement . $hiddenElement;
    }
}
