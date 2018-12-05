<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use common\models\Department;

/**
 * Customer model
 *
 * @property integer $id
 * @property string $name
 * @property integer $avatar
 * @property string $email
 * @property string $phone
 * @property string $address
 * @property date $birthday
 * @property string $gender
 * @property string $description
 * @property integer $department_id
 * @property date $start_date
 * @property date $end_date
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
            self::GENDER_M => Yii::t('app', 'male'),
            self::GENDER_F => Yii::t('app', 'female'),            
        ];
    }

    public function getGenderLabel()
    {
        $labels = self::getStaffGenders();
        return ArrayHelper::getValue($labels, $this->gender);
    }

    public function getDepartment()
    {
        return $this->hasOne(Department::className(), ['id' => 'department_id']);
    }

    public function getDepartmentName()
    {
        return ($this->department) ? $this->department->name : '';
    }

    public function getBranchName()
    {
        return ($this->department) ? $this->department->getBranchLabel() : '';
    }

    public function getBirthdayLeft($differenceFormat = '%a')
    {
        if (!$this->birthday || $this->birthday == '0000-00-00') return '';
        $birthdayThisYear = date('Y') . "-" . date('m-d', strtotime($this->birthday));
        $datetime1 = date_create($birthdayThisYear);
        $datetime2 = date_create(date('Y-m-d'));
        $interval = date_diff($datetime1, $datetime2);
        return $interval->format($differenceFormat);
    }
}
