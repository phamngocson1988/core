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
 * Game model
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
class Game extends ActiveRecord
{
	const STATUS_INVISIBLE = 'N';
    const STATUS_VISIBLE = 'Y';
    const STATUS_DELETE = 'D';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%game}}';
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
            ]
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

    public function attributeLabels() { 

        return  [
            'title' => Yii::t('app', 'title'),
            'content' => Yii::t('app', 'description'),
            'status' => Yii::t('app', 'status'),
            'excerpt' => Yii::t('app', 'excerpt'),
            'unit_name' => Yii::t('app', 'unit_name'),
            'image_id' => Yii::t('app', 'image'),
            'price' => 'Giá bán',
            'meta_title' => Yii::t('app', 'meta_title'),
            'meta_keyword' => Yii::t('app', 'meta_keyword'),
            'meta_description' => Yii::t('app', 'meta_description'),
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

    public function getProducts() 
    {
        return $this->hasMany(Product::className(), ['game_id' => 'id'])
        ->where('status = :status', [':status' => Product::STATUS_VISIBLE]);
    }

    // public function getPrice()
    // {
        // $products = $this->products;
        // $product = reset($products);
        // if ($product) return $product->price;
        // return $this->price;
    // }

    // public function getUnit()
    // {
    //     $products = $this->products;
    //     $product = reset($products);
    //     if ($product) return $product->unit;   
    //     return 1;
    // }

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

    public function getImages()
    {
        return $this->hasMany(GameImage::className(), ['game_id' => 'id']);
    }
}
