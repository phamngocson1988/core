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
        $models = Ads::find()
        ->where([
            'status' => Ads::STATUS_ACTIVE,
            'position' => $this->position
        ])
        ->andWhere(['<=', 'start_date', $now])
        ->andWhere(['>=', 'end_date', $now])
        ->all();
        if (!$models) return '';
        try {
            return $this->render('ads/' . $this->position, [
                'models' => $models, 
            ]);
        } catch (\yii\base\ViewNotFoundException $e) {
            return '';
        }
        
    }
}