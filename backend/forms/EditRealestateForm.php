<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Realestate;
use common\models\RealestateImage;
use yii\helpers\ArrayHelper;

class EditRealestateForm extends Model
{
    public $id;
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
    public $latitude;
    public $longitude;
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

    protected $_realestate;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'title'], 'required'],
            ['status', 'default', 'value' => Realestate::STATUS_SELLING],
            [['excerpt', 'content', 'image_id', 'meta_title', 'meta_keyword', 'meta_description', 'gallery', 'address', 'province_id', 'district_id', 'ward_id', 'direction', 'area', 'price', 'latitude', 'longitude', 'num_bed', 'num_toilet', 'deposit', 'deposit_duration', 'open_at', 'close_at'], 'safe']
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

    public function save()
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $realestate = $this->getRealestate();
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
                $realestate->latitude = $this->latitude;
                $realestate->longitude = $this->longitude;
                $realestate->num_bed = $this->num_bed;
                $realestate->num_toilet = $this->num_toilet;
                $realestate->deposit = $this->deposit;
                $realestate->deposit_duration = $this->deposit_duration;
                $realestate->open_at = $this->open_at;
                $realestate->close_at = $this->close_at;
                $realestate->updated_by = Yii::$app->user->id;
                $realestate->status = $this->status;
                if (!$realestate->save()) {
                    throw new Exception("Error Processing Request", 1);
                }
                
                $this->addGallery();

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

    public function getDirectionList()
    {
        return Realestate::getDirectionList();
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

    public function loadData($id)
    {
        $this->id = $id;
        $realestate = $this->getRealestate();
        $this->title = $realestate->title;
        $this->content = $realestate->content;
        $this->excerpt = $realestate->excerpt;
        $this->image_id = $realestate->image_id;
        $this->meta_title = $realestate->meta_title;
        $this->meta_keyword = $realestate->meta_keyword;
        $this->meta_description = $realestate->meta_description;
        $this->address = $realestate->address;
        $this->province_id = $realestate->province_id;
        $this->district_id = $realestate->district_id;
        $this->ward_id = $realestate->ward_id;
        $this->direction = $realestate->direction;
        $this->area = $realestate->area;
        $this->price = $realestate->price;
        $this->latitude = $realestate->latitude;
        $this->longitude = $realestate->longitude;
        $this->num_bed = $realestate->num_bed;
        $this->num_toilet = $realestate->num_toilet;
        $this->deposit = $realestate->deposit;
        $this->deposit_duration = $realestate->deposit_duration;
        $this->open_at = $realestate->open_at;
        $this->close_at = $realestate->close_at;
        $this->status = $realestate->status;

        
    }

    protected function getRealestate()
    {
        if ($this->_realestate === null) {
            $this->_realestate = Realestate::findOne($this->id);
        }

        return $this->_realestate;
    }
}
