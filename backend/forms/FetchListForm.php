<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;

class FetchListForm extends Model
{
    protected $_query;
    public $orderBy;
    
    protected function buildQuery()
    {
    	return;
    }

    public function getQuery()
    {
        if (!$this->_query) {
            $this->buildQuery();
        }
        return $this->_query;
    }

    public function fetchAll()
    {
    	$_query = $this->getQuery();
    	$query = clone $_query;
        return $query->all();
    }

    public function fetch()
    {
    	$_query = $this->getQuery();
    	$query = clone $_query;
        $pages = $this->getPaging();
        return $query->offset($pages->offset)->limit($pages->limit)->all();
    }

    public function getPaging()
    {
    	$query = $this->getQuery();
        return new Pagination(['totalCount' => $query->count()]);
    }

    public function getSql()
    {
    	$query = $this->getQuery();
    	return $query->createCommand()->sql;
    }

}
