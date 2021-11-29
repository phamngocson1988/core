<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Post;
use common\models\Category;
use common\models\PostCategory;
use yii\helpers\ArrayHelper;
use backend\forms\FetchCategoryForm;
/**
 * EditPostForm is the model behind the contact form.
 */
class EditPostForm extends Model
{
    public $id;
    public $title;
    public $excerpt;
    public $content;
    public $image_id;
    public $categories;
    public $meta_title;
    public $meta_keyword;
    public $meta_description;
    public $status;
    public $hot;
    public $published_at;

    private $_post;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'title', 'content'], 'required'],
            ['id', 'validatePost'],
        ];
    }

    public function attributeHints()
    {
        return ['image_id' => Yii::t('app', 'image_size_at_least', ['size' => '940x630'])];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_DEFAULT] = ['id', 'title', 'content', 'excerpt', 'categories', 'image_id', 'meta_title', 'meta_keyword', 'meta_description', 'status', 'hot', 'published_at'];
        return $scenarios;
    }

    public function save()
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = $this->getPost();
            try {
                $post->title = $this->title;
                $post->content = $this->content;
                $post->excerpt = $this->excerpt;
                $post->image_id = $this->image_id;
                $post->meta_title = $this->meta_title;
                $post->meta_keyword = $this->meta_keyword;
                $post->meta_description = $this->meta_description;
                $post->status = $this->status;
                $post->hot = $this->hot ? 1 : 0;
                if ($post->status === Post::STATUS_SCHEDULED) {
                    if (!$post->published_at
                        || (strtotime($post->published_at) <= strtotime('now'))
                    ) {
                        $post->status = Post::STATUS_VISIBLE;
                        $post->published_at = date('Y-m-d H:i:s');
                    }
                }
                $result = $post->save();

                // categories
                PostCategory::deleteAll(['post_id' => $post->id]);
                $categories = array_filter((array)$this->categories);
                foreach ($categories as $key => $categoryId) {
                    $postCategory = new PostCategory();
                    $postCategory->post_id = $post->id;
                    $postCategory->category_id = $categoryId;
                    $postCategory->is_main = (!$key) ? PostCategory::MAIN_Y : PostCategory::MAIN_N;
                    $postCategory->save();
                }
                
                $transaction->commit();
                return $result;
            } catch (Exception $e) {
                $transaction->rollBack();                
                throw $e;
            } catch (\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
        }
    }

    protected function getPost()
    {
        if ($this->_post === null) {
            $this->_post = Post::findOne($this->id);
        }

        return $this->_post;
    }

    public function loadData($id)
    {
        $this->id = $id;
        $post = $this->getPost();
        $categories = $post->categories;
        $categories = ArrayHelper::map($categories, 'id', 'id');
        $this->title = $post->title;
        $this->content = $post->content;
        $this->excerpt = $post->excerpt;
        $this->categories = $categories;
        $this->image_id = $post->image_id;
        $this->meta_title = $post->meta_title;
        $this->meta_keyword = $post->meta_keyword;
        $this->meta_description = $post->meta_description;
        $this->status = $post->status;
        $this->hot = $post->hot;
    }

    public function hasImage()
    {
        $post = $this->getPost();
        return $post->image;
    }

    public function getImageUrl($size)
    {
        $post = $this->getPost();
        return $post->getImageUrl($size, '');
    }

    public function getCategories($format = '%s')
    {
        $fetchCategoryForm = new FetchCategoryForm([
            'type' => Category::TYPE_POST,
            'visible' => Category::VISIBLE
        ]);
        $categories = $fetchCategoryForm->fetch();

        $categories = ArrayHelper::map($categories, 'id', 'name');
        $categories = array_map(function($name) use ($format) {
            return sprintf($format, $name);
        }, $categories);
        return $categories;
    }

    public function validatePost($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $post = $this->getPost();
            if (!$post) {
                $this->addError($attribute, Yii::t('app', 'invalid_post'));
            }
        }
    }

    public function getStatusList($format = '%s')
    {
        $list = Post::getStatusList();
        $list = array_map(function($name) use ($format) {
            return sprintf($format, $name);
        }, $list);
        return $list;
    }
}
