<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Realestate;
use common\models\RealestateImage;
use yii\helpers\ArrayHelper;

class CreateRealestateForm extends Model
{
    public $title;
    public $content;
    public $excerpt;
    public $image_id;
    
    public $address;
    public $province_id;
    public $district_id;
    public $ward_id;
    public $direction;
    public $area;
    public $price;
    public $google_map;
    public $num_bed;
    public $num_toilet;
    public $deposit;
    public $deposit_duration;
    public $open_at;
    public $close_at;

    

    
    public $meta_title;
    public $meta_keyword;
    public $meta_description;
    public $status = Realestate::STATUS_SELLING;
    public $gallery = [];
    public $options = [];

    protected $id;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            ['status', 'default', 'value' => Realestate::STATUS_SELLING],
            [['excerpt', 'content', 'image_id', 'meta_title', 'meta_keyword', 'meta_description', 'gallery', 'address', 'province_id', 'district_id', 'ward_id', 'direction', 'area', 'price', 'google_map', 'num_bed', 'num_toilet', 'deposit', 'deposit_duration', 'open_at', 'close_at'], 'safe']
        ];
    }

    public function attributeLabels() { 

        return  [
            'title' => Yii::t('app', 'title'),
            'content' => Yii::t('app', 'description'),
            'status' => Yii::t('app', 'status'),
            'options' => Yii::t('app', 'realestate_options'),
            'excerpt' => Yii::t('app', 'excerpt'),
            'image_id' => Yii::t('app', 'image'),
            'meta_title' => Yii::t('app', 'meta_title'),
            'meta_keyword' => Yii::t('app', 'meta_keyword'),
            'meta_description' => Yii::t('app', 'meta_description'),
            'gallery' => Yii::t('app', 'gallery'),
        ];
    }

    // public function validateOptions($attribute, $params)
    // {
    //     foreach ($this->options as $key => $data) {
    //         $option = new CreateRealestateOptionForm($data);
    //         $option->setScenario(CreateRealestateOptionForm::SCENARIO_CREATE_PRODUCT);
    //         if (!$option->validate()) {
    //             foreach ($option->getErrors() as $errKey => $errors) {
    //                 $this->addError("options[$key][$errKey]", reset($errors));
    //             }
    //         }
    //     }
    // }

    public function save()
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $realestate = new Realestate();
                $realestate->title = $this->title;
                $realestate->content = $this->content;
                $realestate->excerpt = $this->excerpt;
                $realestate->image_id = $this->image_id;
                $realestate->meta_title = $this->meta_title;
                $realestate->meta_keyword = $this->meta_keyword;
                $realestate->meta_description = $this->meta_description;

                $realestate->address = $this->address;
                $realestate->province_id = $this->province_id;
                $realestate->district_id = $this->district_id;
                $realestate->ward_id = $this->ward_id;
                $realestate->direction = $this->direction;
                $realestate->area = $this->area;
                $realestate->price = $this->price;
                $realestate->google_map = $this->google_map;
                $realestate->num_bed = $this->num_bed;
                $realestate->num_toilet = $this->num_toilet;
                $realestate->deposit = $this->deposit;
                $realestate->deposit_duration = $this->deposit_duration;
                $realestate->open_at = $this->open_at;
                $realestate->close_at = $this->close_at;

                $realestate->created_by = Yii::$app->user->id;
                $realestate->status = $this->status;
                if (!$realestate->save()) {
                    throw new Exception("Error Processing Request", 1);
                }
                
                $this->id = $realestate->id;

                $this->addGallery();
                // $this->addOptions();

                $transaction->commit();
                return $realestate;
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
        return Realestate::getStatusList();
    }

    protected function addGallery()
    {
        if(!$this->id) return;
        foreach ($this->getGallery() as $imageId) {
            $realestateImage = new RealestateImage();
            $realestateImage->image_id = $imageId;
            $realestateImage->realestate_id = $this->id;
            $realestateImage->save();
        }    
    }

    // protected function addOptions()
    // {
    //     if(!$this->id) return;
    //     foreach ($this->options as $data) {
    //         $option = new CreateRealestateOptionForm($data);
    //         $option->setScenario(CreateRealestateOptionForm::SCENARIO_CREATE_PRODUCT);
    //         $option->realestate_id = $this->id;
    //         $option->save();
    //     }
    // }
}
