<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Post;
use common\models\Category;
use common\models\PostCategory;
use backend\forms\FetchCategoryForm;
use yii\helpers\ArrayHelper;

/**
 * CreatePostForm is the model behind the contact form.
 */
class CreatePostForm extends Model
{
    public $title;
    public $excerpt;
    public $content;
    public $image_id;
    public $type;
    public $status;
    public $hot;
    public $categories;
    public $meta_title;
    public $meta_keyword;
    public $meta_description;
    public $published_at;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content'], 'required'],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_DEFAULT] = ['title', 'content', 'excerpt', 'categories', 'image_id', 'type', 'meta_title', 'meta_keyword', 'meta_description', 'status', 'hot', 'published_at'];
        return $scenarios;
    }

    public function save()
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = $this->getPost();
            try {
                $post->save();
                $newId = $post->id;
                $post->position = $newId;
                $post->save();

                // categories
                $categories = array_filter((array)$this->categories);
                foreach ($categories as $key => $categoryId) {
                    $postCategory = new PostCategory();
                    $postCategory->post_id = $newId;
                    $postCategory->category_id = $categoryId;
                    $postCategory->is_main = (!$key) ? PostCategory::MAIN_Y : PostCategory::MAIN_N;
                    $postCategory->save();
                }

                $transaction->commit();
                return $newId;
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
        $post = new Post();
        
        $post->title = $this->title;
        $post->content = $this->content;
        $post->excerpt = $this->excerpt;
        $post->type = $this->type;
        $post->image_id = $this->image_id;
        $post->meta_title = $this->meta_title;
        $post->meta_keyword = $this->meta_keyword;
        $post->meta_description = $this->meta_description;
        $post->status = $this->status;
        $post->published_at = $this->published_at;
        $post->hot = $this->hot ? 1 : 0;
        if ($post->status === Post::STATUS_SCHEDULED) {
            if (!$post->published_at
                || (strtotime($post->published_at) <= strtotime('now'))
            ) {
                $post->status = Post::STATUS_VISIBLE;
                $post->published_at = date('Y-m-d H:i:s');
            }
        }
        return $post;
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

    public function getStatusList()
    {
        return Post::getStatusList();
    }
}
