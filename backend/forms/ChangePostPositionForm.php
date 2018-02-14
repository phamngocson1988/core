<?php
namespace backend\forms;

use yii\base\Model;
use common\models\Post;
use Yii;

class ChangePostPositionForm extends Model
{
    public $id;
    public $direction;

    protected $_post;

    public function rules()
    {
        return [
            [['id', 'direction'], 'required'],
            ['direction', 'in', 'range' => ['up', 'down']],
            ['id', 'validatePost'],
        ];
    }

    public function process()
    {
        if ($this->validate()) {
            if ($this->direction == 'up') {
                return $this->up();
            } elseif ($this->direction == 'down') {
                return $this->down();
            }
        }
        return false;
    }

    public function up()
    {
        $post = $this->getPost();
        $currentPosition = $post->position;
        $up = Post::find()->where('position > ' . $currentPosition)->orderBy('position desc')->one();
        if ($up) {
            $post->position = $up->position;
            $post->save();

            $up->position = $currentPosition;
            $up->save();
        }
        return true;
    }

    public function down()
    {
        $post = $this->getPost();
        $currentPosition = $post->position;
        $up = Post::find()->where('position < ' . $currentPosition)->orderBy('position asc')->one();
        if ($up) {
            $post->position = $up->position;
            $post->save();

            $up->position = $currentPosition;
            $up->save();
        }
        return true;
    }

    public function validatePost($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $post = $this->getPost();
            if (!$post) {
                $this->addError($attribute, Yii::t('app', 'invalid_post'));
            }
        }
    }

    public function getPost()
    {
        if (!$this->_post) {
            $this->_post = Post::findOne($this->id);
        }
        return $this->_post;
    }
}