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
		// <a href="http://admin.chuchu.com/post/edit?id=1&amp;ref=http%3A%2F%2Fadmin.chuchu.com%2Fpost%2Findex" class="btn btn-xs grey-salsa"><i class="fa fa-pencil"></i></a>
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