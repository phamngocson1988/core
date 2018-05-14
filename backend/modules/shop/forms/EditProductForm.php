<?php

namespace backend\modules\shop\forms;

use Yii;
use yii\base\Model;
use common\modules\shop\models\Product;
use common\modules\shop\models\ProductImage;
use common\modules\shop\models\ProductCategory;
use common\models\Category;
use backend\forms\FetchCategoryForm;
use yii\helpers\ArrayHelper;

class EditProductForm extends Model
{
    public $id;
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

    private $_product;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'title', 'content', 'slug'], 'required'],
            ['id', 'validateProduct'],
            ['status', 'default', 'value' => Product::STATUS_VISIBLE],
            [['price', 'sale_price'], 'filter', 'filter' => function ($value) {
                return str_replace(',', '', $value);
            }],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_DEFAULT] = ['id', 'title', 'slug', 'content', 'excerpt', 'categories', 'image_id', 'price', 'status', 'gallery', 'sale_price', 'meta_title', 'meta_keyword', 'meta_description'];
        return $scenarios;
    }

    public function save()
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $product = $this->getProduct();
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
                $product->updated_at = strtotime('now');
                $product->status = $this->status;
                $result = $product->save();

                // categories
                ProductCategory::deleteAll(['product_id' => $product->id]);
                $categories = array_filter((array)$this->categories);
                foreach ($categories as $key => $categoryId) {
                    $productCategory = new ProductCategory();
                    $productCategory->product_id = $product->id;
                    $productCategory->category_id = $categoryId;
                    $productCategory->is_main = (!$key) ? ProductCategory::MAIN_Y : ProductCategory::MAIN_N;
                    $productCategory->save();
                }

                // ProductImage::DeleteAll(['product_id' => $product->id]);
                // foreach ($this->getGallery() as $key => $imageId) {
                //     $productImage = new ProductImage();
                //     $productImage->image_id = $imageId;
                //     $productImage->product_id = $product->id;
                //     $productImage->save();
                // }

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

    protected function getProduct()
    {
        if ($this->_product === null) {
            $this->_product = Product::findOne($this->id);
        }

        return $this->_product;
    }

    public function loadData($id)
    {
        $this->id = $id;
        $product = $this->getProduct();
        $categories = $product->categories;
        $categories = ArrayHelper::map($categories, 'id', 'id');
        $this->title = $product->title;
        $this->slug = $product->slug;
        $this->content = $product->content;
        $this->excerpt = $product->excerpt;
        $this->image_id = $product->image_id;
        $this->categories = $categories;
        $this->price = $product->price;
        $this->sale_price = $product->sale_price;
        $this->meta_title = $product->meta_title;
        $this->meta_keyword = $product->meta_keyword;
        $this->meta_description = $product->meta_description;
        $this->status = $product->status;
    }

    public function validateProduct($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $product = $this->getProduct();
            if (!$product) {
                $this->addError($attribute, 'Invalid product.');
            }
        }
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

    public function hasImage()
    {
        $product = $this->getProduct();
        return $product->image;
    }
    public function getImageUrl($size)
    {
        $product = $this->getProduct();
        return $product->getImageUrl($size, '');
    }

    public function getGallery()
    {
        $gallery = (array)$this->gallery;
        return array_filter($gallery);
    }

    public function getGalleryImages()
    {
        $product = $this->getProduct();
        return $product->gallery;
    }
}
