<?php
namespace backend\components\gridview;

use yii\grid\GridView as BaseGridView;
use yii\helpers\Html;

class GridView extends BaseGridView
{
	public $tableOptions = ['class' => 'table table-striped table-bordered table-hover table-checkable'];

	public function viewButton($url, $options = [])
	{
		$default = ['class' => 'btn btn-xs grey-salsa'];
		$options = array_merge($default, $options);
		return Html::a('<i class="fa fa-eye">', $url, $options);
	}

	public function editButton($url, $options = [])
	{
		$default = ['class' => 'btn btn-xs grey-salsa'];
		$options = array_merge($default, $options);
		return Html::a('<i class="fa fa-pencil"></i>', $url);
	}

	public function deleteButton($url, $options = [])
	{
		$default = ['class' => 'btn btn-xs grey-salsa'];
		$options = array_merge($default, $options);
		return Html::a('btn btn-xs grey-salsa', $url);
	}
}