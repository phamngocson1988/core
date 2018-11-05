<?php
namespace backend\components\gridview;

use Yii;
use yii\grid\ActionColumn as BaseActionColumn;
use yii\grid\Column;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * ActionColumn is a column for the [[GridView]] widget that displays buttons for viewing and manipulating the items.
 *
 * To add an ActionColumn to the gridview, add it to the [[GridView::columns|columns]] configuration as follows:
 *
 * ```php
 * 'columns' => [
 *     // ...
 *     [
 *         'class' => ActionColumn::className(),
 *         // you may configure additional properties here
 *     ],
 * ]
 * ```
 *
 * For more details and usage information on ActionColumn, see the [guide article on data widgets](guide:output-data-widgets).
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ActionColumn extends Column
{
    /**
     * {@inheritdoc}
     */
    public $headerOptions = ['class' => 'action-column'];
    public $buttons = [];
    public $visibleButtons = [];
    public $buttonOptions = [];
    public $separate = "";
	// <a href="/category/view?id=1" title="Xem" aria-label="Xem" data-pjax="0"><span class="glyphicon glyphicon-eye-open"></span></a>
	// <a href="http://admin.chuchu.com:8080/game/edit?id=1&amp;ref=http%3A%2F%2Fadmin.chuchu.com%3A8080%2Fgame" class="btn btn-xs grey-salsa tooltips" data-container="body" data-original-title="Chỉnh sửa"><i class="fa fa-pencil"></i></a>

	// protected function initDefaultButtons()
 //    {
	// 	$pattern = '/\\{([\w\-\/]+)\\}/';
	// 	preg_match_all($pattern, $this->template, $matches);
	// 	$buttons = ArrayHelper::getValue($matches, '1', []);
 //    	foreach ($this->buttons as $button => $buttonOptions) {
 //    		// $func = sprintf("init%sButton", ucfirst($button));
 //    		// $this->buttons[$button] = $this->$func($options);
 //    		$title = Yii::t('app', $button);
 //    		$options = array_merge([
 //                'title' => $title,
 //                'aria-label' => $title,
 //                'data-pjax' => '0',
 //            ], $buttonOptions);
 //            $icon = Html::tag('i', '', ['class' => "fa fa-pencil"]);
 //            $this->buttons[$button] = Html::a($icon, '#', $options);
 //    	}
 //    }


    protected function renderDataCellContent($model, $key, $index)
    {
        $templates = [];
        foreach ($this->buttons as $button => $settings) {
            if (isset($this->visibleButtons[$button]) && !$this->visibleButtons[$button]) {
                continue;
            }
            $templates[] = $this->renderButton($settings, $model, $key);
        }
        return implode($this->separate, $templates);
    }

    protected function renderButton($settings, $model, $key)
    {
        $title = ArrayHelper::getValue($settings, 'title', '');
        $iconClass = ArrayHelper::getValue($settings, 'icon_class', '');
        $url = ArrayHelper::getValue($settings, 'url', '#');
        $options = ArrayHelper::getValue($settings, 'options', []);\
        $options = array_merge([
            'title' => $title,
            'aria-label' => $title,
            'data-pjax' => '0',
        ], $options);

        $icon = Html::tag('i', $title, ['class' => $iconClass]);
        return Html::a($icon, '#', $options);
    }
}