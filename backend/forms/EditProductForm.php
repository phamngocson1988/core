<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Product;
use common\models\ProductImage;
use yii\helpers\ArrayHelper;

class EditProductForm extends Model
{
    public $id;
    public $title;
    public $content;
    public $excerpt;
    public $image_id;
    public $meta_title;
    public $meta_keyword;
    public $meta_description;
    public $status = Product::STATUS_VISIBLE;
    public $gallery = [];
    public $options = [];

    protected $_product;

    public function rules()
    {
        return [
            [['id', 'title', 'content'], 'required'],
            ['status', 'default', 'value' => Product::STATUS_VISIBLE],
            ['id', 'validateProduct'],
            ['options', 'validateOptions'],
            [['excerpt', 'image_id', 'meta_title', 'meta_keyword', 'meta_description', 'gallery'], 'safe']
        ];
    }

    public function attributeLabels() { 

        return  [
            'id' => Yii::t('app', 'id'),
            'title' => Yii::t('app', 'title'),
            'content' => Yii::t('app', 'description'),
            'status' => Yii::t('app', 'status'),
            'options' => Yii::t('app', 'product_options'),
            'excerpt' => Yii::t('app', 'excerpt'),
            'image_id' => Yii::t('app', 'image'),
            'meta_title' => Yii::t('app', 'meta_title'),
            'meta_keyword' => Yii::t('app', 'meta_keyword'),
            'meta_description' => Yii::t('app', 'meta_description'),
            'gallery' => Yii::t('app', 'gallery'),
        ];
    }

    public function validateOptions($attribute, $params)
    {
        foreach ($this->options as $key => $data) {
            $data = array_filter($data);
            if (ArrayHelper::getValue($data, 'id')) { // edit
                $option = new EditProductOptionForm($data);
            } else { // new
                $option = new CreateProductOptionForm($data);
                $option->setScenario(CreateProductOptionForm::SCENARIO_EDIT_PRODUCT);
            }
            if (!$option->validate()) {
                foreach ($option->getErrors() as $errKey => $errors) {
                    $this->addError("options[$key][$errKey]", reset($errors));
                }
            }   
        }
    }

    public function save()
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $product = $this->getProduct();
                $product->title = $this->title;
                $product->content = $this->content;
                $product->excerpt = $this->excerpt;
                $product->image_id = $this->image_id;
                $product->meta_title = $this->meta_title;
                $product->meta_keyword = $this->meta_keyword;
                $product->meta_description = $this->meta_description;
                $product->status = $this->status;
                if (!$product->save()) {
                    throw new Exception("Error Processing Request", 1);
                }
                $this->addGallery();
                $this->addOptions();
                $transaction->commit();
                return $product;
            } catch (Exception $e) {
                $transaction->rollBack();                
                throw $e;
            } catch (\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
        }
        return false;
    }

    public function getStatusList()
    {
        return Product::getStatusList();
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

    protected function getProduct()
    {
        if ($this->_product === null) {
            $this->_product = Product::findOne($this->id);
        }

        return $this->_product;
    }

    public function setProduct($product) 
    {
        if ($product instanceof Product) {
            $this->_product = $product;
            $this->id = $product->id;
        }
    }

    public function loadData($id)
    {
        $this->id = $id;
        $product = $this->getProduct();
        $this->title = $product->title;
        $this->content = $product->content;
        $this->excerpt = $product->excerpt;
        $this->image_id = $product->image_id;
        $this->meta_title = $product->meta_title;
        $this->meta_keyword = $product->meta_keyword;
        $this->meta_description = $product->meta_description;
        $this->status = $product->status;

        // gallery
        $gallery = $product->gallery;
        $this->gallery = ArrayHelper::getColumn($gallery, 'id');

        // options
        $options = $product->options;
        $this->options = ArrayHelper::toArray($options, ['id', 'product_id', 'title', 'price', 'gems']);
    }

    protected function getGallery()
    {
        $gallery = (array)$this->gallery;
        $gallery = array_filter($gallery);
        $gallery = array_unique($gallery);
        return $gallery;
    }

    protected function addGallery()
    {
        if(!$this->id) return;
        $oldProductImages = ProductImage::findAll(['product_id' => $this->id]);
        foreach ($oldProductImages as $oldImage) {
            $oldImage->delete();
        }

        foreach ($this->getGallery() as $imageId) {
            $productImage = new ProductImage();
            $productImage->image_id = $imageId;
            $productImage->product_id = $this->id;
            $productImage->save();
        }    
    }

    protected function addOptions()
    {
        if(!$this->id) return;
        $product = $this->getProduct();
        $options = $product->options;
        $oldOptionIds = ArrayHelper::getColumn($options, 'id');

        $newOptionIds = ArrayHelper::getColumn($this->options, 'id');
        $removedIds = array_diff($oldOptionIds, $newOptionIds);

        // Remove 
        foreach ($options as $option) {
            if (in_array($option->id, $removedIds)) {
                $option->delete();
            }
        }
        foreach ($this->options as $data) {
            $data = array_filter($data);
            if (ArrayHelper::getValue($data, 'id')) { // edit
                $option = new EditProductOptionForm($data);
            } else { // new
                $option = new CreateProductOptionForm($data);
                $option->setScenario(CreateProductOptionForm::SCENARIO_EDIT_PRODUCT);
            }
            $option->save();  
        }
    }
}
