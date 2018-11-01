<?php
namespace backend\forms;

use yii\base\Model;
use common\models\Category;
use Yii;

class FetchCategoryForm extends Model
{
    public $type;
    public $q;
    public $visible;

    private $_command;

    public function rules()
    {
        return [
            [['q', 'visible'], 'trim'],
            ['type', 'required'],
        ];
    }
    
    public function fetch()
    {
        if ($this->validate()) {
            $command = $this->getCommand();
            return $command->all();
        }
        return false;        
    }

    protected function createCommand()
    {
        $command = Category::find();
        $command->where(['type' => $this->type]);
        
        if ($this->q) {
            $command->orWhere(['like', 'name', $this->q]);
        }
        
        if ($this->visible) {
            $command->andWhere(['visible' => $this->visible]);
        }
        $command->orderBy('id desc');
        return $command;
    }

    public function getCategoryByParent($parent = null)
    {
        $categories = $this->getList();
        $categories = array_filter($categories, function ($category) use ($parent) { 
            return ($category->parent_id == $parent); 
        }); 
        return $categories;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->_command = $this->createCommand();
        }
        return $this->_command;
    }

    public function getProvider()
    {
        $command = $this->getCommand();
        $provider = new \yii\data\ActiveDataProvider([
            'query' => $command,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'name' => SORT_DESC,
                    'slug' => SORT_ASC, 
                ]
            ],
        ]);
        return $provider;
    }

    public function getGridView()
    {
        return \backend\components\gridview\GridView::widget([
            'dataProvider' => $this->getProvider(),
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'label' => Yii::t('app', 'image'),
                    'format' => 'html',
                    'attribute' => 'image_id',
                    'value' => function($model) {
                        return \yii\helpers\Html::img($model->getImageUrl('50x50'), ['width' => '50px', 'height' => '50px']);
                    }
                ],
                [
                    'label' => Yii::t('app', 'name'),
                    'format' => 'text',
                    'attribute' => 'name'
                ],
                [
                    'label' => Yii::t('app', 'slug'),
                    'format' => 'text',
                    'attribute' => 'slug'
                ],
                ['class' => 'yii\grid\ActionColumn']
            ]
        ]);
    }
}