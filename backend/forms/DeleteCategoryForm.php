<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Category;

class DeleteCategoryForm extends Model
{
    public $id;

    private $_category;

	public function rules()
    {
        return [
            ['id', 'required'],
            ['id', 'validateCategory'],
        ];
    }

    public function delete()
    {
    	if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            $category = $this->getCategory();
            try {
            	$result = $category->delete();
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

    public function validateCategory($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $category = $this->getCategory();
            if (!$category) {
                $this->addError($attribute, 'Invalid category.');
            }
        }
    }

    protected function getCategory()
    {
        if ($this->_category === null) {
            $this->_category = Category::findOne($this->id);
        }

        return $this->_category;
    }
}
