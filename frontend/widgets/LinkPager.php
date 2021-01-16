<?php
namespace frontend\widgets;

class LinkPager extends \yii\widgets\LinkPager
{
	public $options = ['class' => 'pagination justify-content-center'];
    public $pageCssClass = 'page-item';
    public $prevPageCssClass = 'page-item';
    public $nextPageCssClass = 'page-item';
    public $linkOptions = ['class' => 'page-link'];
    public $prevPageLabel= '<img class="icon" src="/images/icon/back.svg"/>';
    public $nextPageLabel = '<img class="icon" src="/images/icon/next.svg"/>';
}