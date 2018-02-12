<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\Image;
use yii\helpers\ArrayHelper;

/**
 * Category model
 *
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property string $slug
 * @property integer $parent_id
 * @property integer $visible
 * @property string $meta_title
 * @property string $meta_keyword
 * @property string $meta_description
 * @property string $icon
 * @property integer $image_id
 */
class Category extends ActiveRecord
{
    const INVISIBLE = 'N';
    const VISIBLE = 'Y';

    const TYPE_POST = 'post';
    const TYPE_PRODUCT = 'product';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    public static function getVisibleStatus()
    {
        return [
            self::INVISIBLE => 'Disable',
            self::VISIBLE => 'Enable'
        ];
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

    public function getImage() 
    {
        return $this->hasOne(Image::className(), ['id' => 'image_id']);
    }

    public function getImageUrl($size = null, $default = '/images/noimage.png')
    {
        $image = $this->image;
        if ($image) {
            return $image->getUrl($size);
        }
        return $default;
    }

    public function isVisible()
    {
        return $this->visible === self::VISIBLE;
    }

    public function getVisibleLable()
    {
        $labels = self::getVisibleStatus();
        return ArrayHelper::getValue($labels, $this->visible);
    }

    public function getMetaTitle()
    {
        return ($this->meta_title) ? $this->meta_title : $this->name;
    }

    public function getMetaDescription()
    {
        return ($this->meta_description) ? $this->meta_description : $this->name;
    }

    public function getMetaKeyword()
    {
        return $this->meta_keyword;
    }

    public function getIcon()
    {
        return ($this->icon) ? $this->icon : 'fa-sun-o';
    }
}
