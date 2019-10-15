<?php
namespace backend\components\gridview;

use Yii;
use yii\grid\DataColumn;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\models\Image;
/**
 * ImageColumn displays a column of row numbers (1-based).
 *
 * To add a ImageColumn to the [[GridView]], add it to the [[GridView::columns|columns]] configuration as follows:
 *
 * ```php
 * 'columns' => [
 *     // ...
 *     [
 *         'class' => 'backend\components\gridview\ImageColumn',
 *         // you may configure additional properties here
 *     ],
 * ]
 * ```
 *
 * For more details and usage information on SerialColumn, see the [guide article on data widgets](guide:output-data-widgets).
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ImageColumn extends DataColumn
{
    public $image_options = [];
    
    /**
     * {@inheritdoc}
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $width = ArrayHelper::getValue($this->image_options, 'width');
        $height = ArrayHelper::getValue($this->image_options, 'height');
        $size = ($width && $height) ? sprintf("%sx%s", $width, $height) : null;
        $imageId = $key;
        $image = Image::findOne($imageId);
        $url = (!$image) ? Yii::$app->image->getDefaultImageUrl() : $url = $image->getUrl($size);
        return Html::img($url, $this->image_options);
    }
}
