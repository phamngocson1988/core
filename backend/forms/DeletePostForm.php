<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Post;

class DeletePostForm extends Model
{
    public $id;

    private $_post;

	public function rules()
    {
        return [
            ['id', 'required'],
            ['id', 'validatePost'],
        ];
    }

    public function delete()
    {
    	if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = $this->getPost();
            try {
            	$result = $post->delete();
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
        return false;
    }

    public function validatePost($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $post = $this->getPost();
            if (!$post) {
                $this->addError($attribute, 'Invalid post.');
            }
        }
    }

    protected function getPost()
    {
        if ($this->_post === null) {
            $this->_post = Post::findOne($this->id);
        }

        return $this->_post;
    }
}
