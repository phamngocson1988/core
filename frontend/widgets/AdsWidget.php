<?php
namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use frontend\models\Ads;

class AdsWidget extends Widget
{
    public $position;

    public function run()
    {
        if (!$this->position) return '';
        $now = date('Y-m-d H:i:s');
        $model = Ads::find()
        ->where([
            'status' => Ads::STATUS_ACTIVE,
            'position' => $this->position
        ])
        ->andWhere(['<=', 'start_date', $now])
        ->andWhere(['>=', 'end_date', $now])
        ->one();
        if (!$model) return '';
        try {
            return $this->render('ads/' . $model->position, [
                'model' => $model, 
            ]);
        } catch (\yii\base\ViewNotFoundException $e) {
            return '';
        }
        
    }
}