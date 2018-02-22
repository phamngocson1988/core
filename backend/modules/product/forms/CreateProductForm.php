<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Product;
use common\models\ProductImage;
use common\models\ProductCategory;
use common\models\Category;
use backend\forms\FetchCategoryForm;
use yii\helpers\ArrayHelper;

class CreateProductForm extends Model
{
    public $title;
    public $slug;
    public $content;
    public $excerpt;
    public $categories;
    public $image_id;
    public $price;
    public $sale_price;
    public $meta_title;
    public $meta_keyword;
    public $meta_description;
    public $status = Product::STATUS_VISIBLE;
    public $gallery = [];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content', 'slug'], 'required'],
            ['status', 'default', 'value' => Product::STATUS_VISIBLE],
            [['price', 'sale_price'], 'filter', 'filter' => function ($value) {
                return str_replace(',', '', $value);
            }],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_DEFAULT] = ['title', 'slug', 'content', 'excerpt', 'categories', 'image_id', 'price', 'status', 'gallery', 'sale_price', 'meta_title', 'meta_keyword', 'meta_description'];
        return $scenarios;
    }

    public function save()
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $product = new Product();
                $product->title = $this->title;
                $product->slug = $this->slug;
                $product->content = $this->content;
                $product->excerpt = $this->excerpt;
                $product->image_id = $this->image_id;
                $product->price = $this->price;
                $product->sale_price = $this->sale_price;
                $product->meta_title = $this->meta_title;
                $product->meta_keyword = $this->meta_keyword;
                $product->meta_description = $this->meta_description;
                $product->created_by = Yii::$app->user->id;
                $product->created_at = strtotime('now');
                $product->updated_at = strtotime('now');
                $product->status = $this->status;
                $product->save();
                $newId = $product->id;

                // categories
                // foreach ((array)$this->categories as $key => $categoryId) {
                //     $productCategory = new ProductCategory();
                //     $productCategory->product_id = $newId;
                //     $productCategory->category_id = $categoryId;
                //     $productCategory->is_main = (!$key) ? ProductCategory::MAIN_Y : ProductCategory::MAIN_N;
                //     $productCategory->save();
                // }

                // galleries
                // foreach ($this->getGallery() as $key => $imageId) {
                //     $productImage = new ProductImage();
                //     $productImage->image_id = $imageId;
                //     $productImage->product_id = $newId;
                //     $productImage->save();
                // }
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

    public function getGallery()
    {
        $gallery = (array)$this->gallery;
        return array_filter($gallery);
    }

    public function getCategories($format = '%s')
    {
        $fetchCategoryForm = new FetchCategoryForm([
            'type' => Category::TYPE_PRODUCT,
            'visible' => Category::VISIBLE
        ]);
        $categories = $fetchCategoryForm->fetch();

        $categories = ArrayHelper::map($categories, 'id', 'name');
        $categories = array_map(function($name) use ($format) {
            return sprintf($format, $name);
        }, $categories);
        return $categories;
    }
}
