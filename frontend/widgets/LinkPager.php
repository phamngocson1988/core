<?php
namespace frontend\widgets;

class LinkPager extends \yii\widgets\LinkPager
{
	public $options = ['class' => 'pagination justify-content-center'];
    public $pageCssClass = 'page-item';
    public $prevPageCssClass = 'page-item';
    public $nextPageCssClass = 'page-item';
    public $linkOptions = ['class' => 'page-link'];
    public $prevPageLabel= '<a class="page-link" href="javascript:;">Previous</a>';
    public $nextPageLabel= '<a class="page-link" href="javascript:;">Next</a>';
}