<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Image;

/**
 * DeleteImageForm is the model behind the contact form.
 */
class DeleteImageForm extends Model
{
    public $id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id', 'required'],
        ];
    }

    public function delete()
    {
        if ($this->validate()) {
            return Image::deleteAll(['id' => $this->getId()]);
        }
        return false;
    }

    public function getId()
    {
        return $this->id;
    }
}
