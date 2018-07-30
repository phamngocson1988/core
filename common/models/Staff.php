<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Customer model
 *
 * @property integer $id
 * @property string $name
 * @property integer $avatar
 * @property string $email
 * @property string $phone
 * @property string $address
 * @property integer $birthday
 * @property string $gender
 * @property string $description
 * @property string $department
 * @property integer $start_date
 * @property integer $end_date
 * @property integer $created_at
 * @property integer $updated_at
 */
class Staff extends ActiveRecord
{
    const GENDER_M = 'M';
    const GENDER_F = 'F';


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%staff}}';
    }

    public static function getStaffGenders()
    {
        return [
            self::GENDER_M => 'Male',
            self::GENDER_F => 'Female',            
        ];
    }

    public function getGenderLabel()
    {
        $labels = self::getStaffGenders();
        return ArrayHelper::getValue($labels, $this->gender);
    }
}
