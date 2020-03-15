<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Image;

/**
 * ChangeAvatarForm is the model behind the contact form.
 */
class ChangeAvatarForm extends Model
{
    public $image_id;

    private $_image;

     /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['image_id', 'required'],
            ['image_id', 'validateImage']
        ];
    }

    public function change()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            if ($user->avatar === $this->image_id) {
                return true;
            }
            $user->avatar = $this->image_id;
            $user->updated_at = strtotime('now');
            return $user->save(false, ['avatar', 'updated_at']);
        }
        return false;
    }

    public function validateImage($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $image = $this->getImage();
            $user = $this->getUser();
            if (!$image) {
                $this->addError($attribute, Yii::t('app', 'invalid_image'));
            // } elseif ($image->created_by != $user->getId()) {
            //     $this->addError($attribute, Yii::t('app', 'invalid_image'));
            }
        }
    }

    protected function getImage()
    {
        if ($this->_image === null) {
            $this->_image = Image::findOne($this->image_id);
        }

        return $this->_image;
    }

    protected function getUser()
    {
        return Yii::$app->user->getIdentity();
    }
}
