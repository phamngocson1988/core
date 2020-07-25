<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\HotNew;

class EditHotNewForm extends Model
{
    public $id;
    public $title;
    public $link;
    public $image_id;

    protected $_hotnew;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'title', 'link', 'image_id'], 'required'],
        ];
    }

    public function save()
    {
        $post = $this->getHotNew();
        $post->title = $this->title;
        $post->link = $this->link;
        $post->image_id = $this->image_id;
        return $post->save();
    }

    public function getHotNew()
    {
        if (!$this->_hotnew) {
            $this->_hotnew = HotNew::findOne($this->id);
        }
        return $this->_hotnew;
    }

    public function loadData()
    {
        $hotnew = $this->getHotNew();
        $this->title = $hotnew->title;
        $this->link = $hotnew->link;
        $this->image_id = $hotnew->image_id;
    }
}
