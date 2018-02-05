<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Promotion;

/**
 * CreatePromotionForm is the model behind the contact form.
 */
class CreatePromotionForm extends Model
{
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content', 'slug', 'type', 'status'], 'required'],
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
        $scenarios[self::SCENARIO_DEFAULT] = ['title', 'content', 'excerpt', 'slug', 'image_id', 'type', 'meta_title', 'meta_keyword', 'meta_description', 'status', 'from_date', 'to_date'];
        return $scenarios;
    }

    public function save()
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $promotion = new Promotion();
                $promotion->title = $this->title;
                $promotion->content = $this->content;
                $promotion->excerpt = $this->excerpt;
                $promotion->slug = $this->slug;
                $promotion->type = $this->type;
                $promotion->image_id = $this->image_id;
                $promotion->meta_title = $this->meta_title;
                $promotion->meta_keyword = $this->meta_keyword;
                $promotion->meta_description = $this->meta_description;
                $promotion->status = $this->status;
                $promotion->from_date = ($this->from_date) ? strtotime($this->from_date) : null;
                $promotion->to_date = ($this->to_date) ? strtotime($this->to_date) : null;
                $promotion->created_by = Yii::$app->user->id;
                $promotion->created_at = strtotime('now');
                $promotion->save();
                $newId = $promotion->id;

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
}
