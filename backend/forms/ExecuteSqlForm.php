<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\CurrencySetting;
use yii\helpers\ArrayHelper;

class ExecuteSqlForm extends Model
{
    public $sql;

    public function rules()
    {
        return [
            ['sql', 'trim'],
            ['sql', 'required'],
        ];
    }

    public function run()
    {
        if (!$this->validate()) return false;
        try {
            $connection = Yii::$app->db;
            $result = $connection->createCommand($this->sql)->execute();
            return true;
        } catch ( yii\db\Exception $e) {
            $this->addError('sql', $e->getMessage());
            return false;
        } catch (\Exception $e) {
            $this->addError('sql', $e->getMessage());
            return false;
        }
    }
}
