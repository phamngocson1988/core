<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\User;
use common\models\Image;
use common\models\Product;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * Product model
 *
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property string $excerpt
 * @property string $content
 * @property integer $image_id
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

    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'slugAttribute' => 'slug',
                'immutable' => true,
                'ensureUnique'=>true,
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => date('Y-m-d H:i:s')
            ],
        ];
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_INVISIBLE => 'Invisible',
            self::STATUS_VISIBLE => 'Visible',
            self::STATUS_DELETE => 'Deleted'
        ];
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

    public function getOptions() 
    {
        return $this->hasMany(Option::className(), ['product_id' => 'id'])
        ->where('status = :status', [':status' => Option::STATUS_VISIBLE]);
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
