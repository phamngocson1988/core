<?php
namespace common\modules\shop\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\Category;
use common\models\User;
use common\models\Image;

/**
 * Product model
 *
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property string $excerpt
 * @property string $content
 * @property integer $image_id
 * @property integer $price
 * @property integer $sale_price
 * @property string $meta_title
 * @property string $meta_keyword
 * @property string $meta_description
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $deleted_by
 * @property integer $deleted_at
 * @property string $status
 */
class Product extends ActiveRecord
{
	const STATUS_INVISIBLE = 'N';
    const STATUS_VISIBLE = 'Y';
    const STATUS_DELETE = 'D';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product}}';
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_INVISIBLE => 'Invisible',
            self::STATUS_VISIBLE => 'Visible',
            self::STATUS_DELETE => 'Deleted'
        ];
    }

    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['id' => 'category_id'])
            ->viaTable(ProductCategory::tableName(), ['product_id' => 'id']);
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

    public function getCreatedAt($format = false)
    {
        if ($format == true) {
            return date(Yii::$app->params['date_format'], $this->created_at);
        }
        return $this->created_at;
    }

    public function getUpdatedAt($format = false)
    {
        if ($format == true) {
            return date(Yii::$app->params['date_format'], $this->updated_at);
        }
        return $this->updated_at;
    }

    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getCreatorName()
    {
        $user = $this->creator;
        if ($user) {
            return $user->name;
        }
        return '';
    }

    public function getGallery()
    {
        return $this->hasMany(Image::className(), ['id' => 'image_id'])
            ->viaTable(ProductImage::tableName(), ['product_id' => 'id']);
    }

    public function getPrice($format = false)
    {
        if ($format === true) {
            return number_format((int)$this->price);
        }
        return (int)$this->price;
    }

    public function getSalePrice($format = false)
    {
        if ($format === true) {
            return number_format((int)$this->sale_price);
        }
        return (int)$this->sale_price;
    }

    public function isDiscount()
    {
        return ($this->getSalePrice() < $this->getPrice());
    }

    public function getDiscount($format = false)
    {
        if ($this->isDiscount() && !$this->discount) {
            $this->discount = ($this->getSalePrice() - $this->getPrice()) * 100 / $this->getPrice();
        }
        
        if ($format === true) {
            return number_format($this->discount, 1);
        }
        return $this->discount;
    }

    public function getMetaTitle()
    {
        return ($this->meta_title) ? $this->meta_title : $this->title;
    }

    public function getMetaDescription()
    {
        return ($this->meta_description) ? $this->meta_description : $this->title;
    }

    public function getMetaKeyword()
    {
        return $this->meta_keyword;
    }    

    public function getExcerpt($number = null) 
    {
        $excerpt = ($this->excerpt) ? $this->excerpt : strip_tags($this->content);
        if ($number && !$this->excerpt) {
            return substr($excerpt, 0, $number);
        }
        return $excerpt;
    }   
}
