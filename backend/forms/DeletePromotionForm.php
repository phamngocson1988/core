<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Promotion;

class DeletePromotionForm extends Model
{
    public $id;

    private $_promotion;

	public function rules()
    {
        return [
            ['id', 'required'],
            ['id', 'validatePromotion'],
        ];
    }

    public function delete()
    {
    	if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            $promotion = $this->getPromotion();
            try {
            	$result = $promotion->delete();
                $transaction->commit();
                return $result;
            } catch (Exception $e) {
                $transaction->rollBack();                
                throw $e;
            } catch (\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
        }
        return false;
    }

    public function validatePromotion($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $promotion = $this->getPromotion();
            if (!$promotion) {
                $this->addError($attribute, 'Invalid promotion.');
            }
        }
    }

    protected function getPromotion()
    {
        if ($this->_promotion === null) {
            $this->_promotion = Promotion::findOne($this->id);
        }

        return $this->_promotion;
    }
}
