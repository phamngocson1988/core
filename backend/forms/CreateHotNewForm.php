<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\HotNew;

class CreateHotNewForm extends Model
{
    public $title;
    public $link;
    public $image_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'link', 'image_id'], 'required'],
        ];
    }

    public function save()
    {
        $post = new HotNew();
        $post->title = $this->title;
        $post->link = $this->link;
        $post->image_id = $this->image_id;
        return $post->save();
    }
}
