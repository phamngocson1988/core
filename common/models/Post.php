<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\Image;
use common\models\Category;
use common\models\PostCategory;
use common\models\User;
use yii\behaviors\SluggableBehavior;

/**
 * Post model
 *
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property string $excerpt
 * @property string $content
 * @property integer $image_id
 * @property string $type
 * @property string $meta_title
 * @property string $meta_keyword
 * @property string $meta_description
 * @property string $status
 * @property int $position
 * @property integer $created_at
 * @property integer $created_by
 */
class Post extends ActiveRecord
{

    const POST_TYPE_POST = 'post';
    const POST_TYPE_PAGE = 'page';

    const STATUS_INVISIBLE = 5;
    const STATUS_VISIBLE = 10;
    const STATUS_DELETE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post}}';
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

    public function getCreatedAt($format = false, $default = 'F j, Y, g:i a')
    {
        if ($format == true) {
            return date($default, $this->created_at);
        }
        return $this->created_at;
    }


    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getCreatorName()
    {
        $creator = $this->creator;
        if ($creator) {
            return $creator->getName();
        }
        return '';
    }

    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['id' => 'category_id'])
            ->viaTable(PostCategory::tableName(), ['post_id' => 'id']);
    }

    public function getMetaTitle()
    {
        return ($this->meta_title) ? $this->meta_title : $this->title;
    }

    public function getMetaDescription()
    {
        return ($this->meta_description) ? $this->meta_description : $this->content;
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
