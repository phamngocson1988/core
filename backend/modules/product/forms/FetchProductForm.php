<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Product;
use common\models\Category;
use backend\forms\FetchCategoryForm;
use yii\helpers\ArrayHelper;

class FetchProductForm extends Model
{
    public $q;
    public $status;
    protected $_command;

    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = Product::find();
        $command->with('categories');
        $command->with('creator');
        $command->with('image');

        if ($this->status) {
            $command->andWhere(['status' => $this->status]);
        }

        if ($this->q) {
            $command->andWhere(['like', 'title', $this->q]);
        }

        $this->_command = $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }

    public function getCategories()
    {
        $fetchCategoryForm = new FetchCategoryForm(['type' => Category::TYPE_PRODUCT]);
        $categories = $fetchCategoryForm->fetch();
        return ArrayHelper::map($categories, 'id', 'name');
    }
}
