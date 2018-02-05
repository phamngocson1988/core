<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Promotion;

/**
 * EditPromotionForm is the model behind the contact form.
 */
class EditPromotionForm extends Model
{
    public $id;
    public $title;
    public $slug;
    public $excerpt;
    public $content;
    public $image_id;
    public $type;
    public $meta_title;
    public $meta_keyword;
    public $meta_description;
    public $status;
    public $from_date;
    public $to_date;

    private $_promotion;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'title', 'content', 'slug', 'type', 'status'], 'required'],
            ['id', 'validatePromotion'],
        ];
    }

    public function init()
    {
        parent::init();
        $this->type = (array_key_exists($this->type, Promotion::getTypeList())) ? $this->type : Promotion::TYPE_PUBLIC;
        $this->status = (array_key_exists($this->status, Promotion::getStatusList())) ? $this->status : Promotion::STATUS_VISIBLE;
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_DEFAULT] = ['id', 'title', 'content', 'excerpt', 'slug', 'image_id', 'type', 'meta_title', 'meta_keyword', 'meta_description', 'status', 'from_date', 'to_date'];
        return $scenarios;
    }

    public function save()
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            $promotion = $this->getPromotion();
            try {
                $promotion->title = $this->title;
                $promotion->content = $this->content;
                $promotion->excerpt = $this->excerpt;
                $promotion->slug = $this->slug;
                $promotion->image_id = $this->image_id;
                $promotion->meta_title = $this->meta_title;
                $promotion->meta_keyword = $this->meta_keyword;
                $promotion->meta_description = $this->meta_description;
                $promotion->status = $this->status;
                $promotion->from_date = ($this->from_date) ? strtotime($this->from_date) : null;
                $promotion->to_date = ($this->to_date) ? strtotime($this->to_date) : null;
                $result = $promotion->save();

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

    protected function getPromotion()
    {
        if ($this->_promotion === null) {
            $this->_promotion = Promotion::findOne($this->id);
        }

        return $this->_promotion;
    }

    public function loadData($id)
    {
        $this->id = $id;
        $promotion = $this->getPromotion();
        $this->title = $promotion->title;
        $this->slug = $promotion->slug;
        $this->content = $promotion->content;
        $this->excerpt = $promotion->excerpt;
        $this->image_id = $promotion->image_id;
        $this->meta_title = $promotion->meta_title;
        $this->meta_keyword = $promotion->meta_keyword;
        $this->meta_description = $promotion->meta_description;
        $this->status = $promotion->status;
        $this->from_date = ($promotion->from_date) ? date(Yii::$app->params['date_format'], $promotion->from_date) : null;
        $this->to_date = ($promotion->to_date) ? date(Yii::$app->params['date_format'], $promotion->to_date) : null;
    }

    public function hasImage()
    {
        $promotion = $this->getPromotion();
        return $promotion->image;
    }

    public function getImageUrl($size)
    {
        $promotion = $this->getPromotion();
        return $promotion->getImageUrl($size, '');
    }

    public function getTypeList($format = '%s')
    {
        $list = Promotion::getTypeList();
        $list = array_map(function($name) use ($format) {
            return sprintf($format, $name);
        }, $list);
        return $list;
    }

    public function getStatusList($format = '%s')
    {
        $list = Promotion::getStatusList();
        $list = array_map(function($name) use ($format) {
            return sprintf($format, $name);
        }, $list);
        return $list;
    }

    public function validatePromotion($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $promotion = $this->getPromotion();
            if (!$promotion) {
                $this->addError($attribute, 'Invalid promotion.');
            }
        }
    }

}
