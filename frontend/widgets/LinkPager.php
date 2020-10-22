<?php
namespace frontend\widgets;

use Yii;

class LinkPager extends \yii\widgets\LinkPager
{
	public $options = ['class' => 'pagination justify-content-center'];
    public $pageCssClass = 'page-item';
    public $prevPageCssClass = 'page-item';
    public $nextPageCssClass = 'page-item';
    public $linkOptions = ['class' => 'page-link'];
    public $prevPageLabel= 'Privious';
    public $nextPageLabel= 'Next';
    public function init() 
    {
    	parent::init();
    	$this->prevPageLabel = '<a class="page-link" href="javascript:;">' . Yii::t('app', 'page_privious') . '</a>';
    	$this->nextPageLabel = '<a class="page-link" href="javascript:;">' . Yii::t('app', 'page_next') . '</a>';
    }
}