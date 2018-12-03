<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Product;
use common\models\ProductImage;
use yii\helpers\ArrayHelper;

class CreateProductForm extends Model
{
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

    protected $id;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content'], 'required'],
            ['status', 'default', 'value' => Product::STATUS_VISIBLE],
            ['options', 'validateOptions'],
            [['excerpt', 'image_id', 'meta_title', 'meta_keyword', 'meta_description', 'gallery'], 'safe']
        ];
    }

    public function attributeLabels() { 

        return  [
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
            $option = new CreateProductOptionForm($data);
            $option->setScenario(CreateProductOptionForm::SCENARIO_NEW_PRODUCT);
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
                $product = new Product();
                $product->title = $this->title;
                $product->content = $this->content;
                $product->excerpt = $this->excerpt;
                $product->image_id = $this->image_id;
                $product->meta_title = $this->meta_title;
                $product->meta_keyword = $this->meta_keyword;
                $product->meta_description = $this->meta_description;
                $product->created_by = Yii::$app->user->id;
                $product->status = $this->status;
                $product->save();
                $this->id = $product->id;

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

    protected function getGallery()
    {
        $gallery = (array)$this->gallery;
        $gallery = array_filter($gallery);
        $gallery = array_unique($gallery);
        return $gallery;
    }

    public function getStatusList()
    {
        return Product::getStatusList();
    }

    protected function addGallery()
    {
        if (!$this->id) return;
        foreach ($this->getGallery() as $imageId) {
            $productImage = new ProductImage();
            $productImage->image_id = $imageId;
            $productImage->product_id = $newId;
            $productImage->save();
        }    
    }

    protected function addOptions()
    {
        if (!$this->id) return;
        foreach ($this->options as $data) {
            $option = new CreateProductOptionForm($data);
            $option->setScenario(CreateProductOptionForm::SCENARIO_NEW_PRODUCT);
            $option->product_id = $this->id;
            $option->save();
        }
    }
}
