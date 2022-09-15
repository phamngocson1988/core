<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\ResellerPrice;

/**
 * DeleteResellerPriceForm is the model behind the contact form.
 */
class DeleteResellerPriceForm extends Model
{
    public $reseller_id;
    public $game_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reseller_id', 'game_id'], 'required'],
        ];
    }

    public function run()
    {
        if (!$this->validate()) {
            return false;
        }
        return ResellerPrice::deleteAll(['reseller_id' => $this->reseller_id, 'game_id' => $this->game_id]);
    }
}
