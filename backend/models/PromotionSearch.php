<?php

namespace backend\models;

use Yii;
use yii\data\ActiveDataProvider;
use common\models\Promotion;

/**
 * PromotionSearch represents the model behind the search form about `common\models\Promotion`.
 */
class PromotionSearch extends Promotion
{

    public function search($params)
    {
        $query = Promotion::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'status' => $this->status,
        ]);

        return $dataProvider;
    }
}
