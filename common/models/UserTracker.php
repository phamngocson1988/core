<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use common\models\User;
use common\models\Country;

/**
 * UserTracker model
 */
class UserTracker extends ActiveRecord
{
	const POTENTIAL_COLUMNS = ['question_1', 'question_2', 'question_3', 'question_4'];
	const TARGET_COLUMNS = ['question_5', 'question_6', 'question_7', 'question_8', 'question_9'];
    const CHANNELS = [
        'facebook' => 'Facebook', 
        'google'  => 'Google',
        'twitter'  => 'Twitter',
        'instagram'  => 'Instagram',
        'whatsapp'  => 'Whatsapp',
        'telegram'  => 'Telegram',
        'line'  => 'Line',
        'skype'  => 'Skype',
        'Email' => 'Email',
    ];
    const QUESTIONS = [
        [
            'key' => 'question_1',
            'question' => 'Có tham gia hội nhóm game',
            'point_yes' => 1,
            'point_no' => 0
        ],
        [
            'key' => 'question_2',
            'question' => 'Có tương tác tích cực (tương tác ít nhất 03 lần) trên group/ cộng đồng game.',
            'point_yes' => 1,
            'point_no' => 0
        ],	
        [
            'key' => 'question_3',
            'question' => 'Là bạn của KHMT và có comment vào bài viết liên quan đến sp/dv KG đang cung cấp. (cần xem xét thêm nội dung)',
            'point_yes' => 1,
            'point_no' => 0
        ],	 
        [
            'key' => 'question_4',
            'question' => 'Có thông tin, avatar, bài đăng',
            'point_yes' => 0,
            'point_no' => -2
        ],	
        [
            'key' => 'question_5',
            'question' => 'Đã từng/ muốn trải nghiệm dịch vụ',
            'point_yes' => 1,
            'point_no' => 0
        ],	 
        [
            'key' => 'question_6',
            'question' => 'Sẵn sàng chi trả',
            'point_yes' => 1,
            'point_no' => 0
        ],	 
        [
            'key' => 'question_7',
            'question' => 'Có nhu cầu nạp trong thời gian gần nhất (trong vòng 1-3 ngày)',
            'point_yes' => 2,
            'point_no' => 1
        ],	 
        [
            'key' => 'question_8',
            'question' => 'Có người quen đã từng sử dụng qua loiaj hình dịch vụ này',
            'point_yes' => 1,
            'point_no' => 0
        ],	 
        [
            'key' => 'question_9',
            'question' => 'Đã từng nghe qua Kinggems (social/ bạn bè)',
            'point_yes' => 2,
            'point_no' => 0
        ]
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%lead_tracker}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => date('Y-m-d H:i:s')
            ],
        ];
    }

    public function getSaler()
    {
        if (!$this->saler_id) return null;
        return $this->hasOne(User::className(), ['id' => 'saler_id']);
    }

    public function getGame()
    {
        if (!$this->game_id) return null;
        return $this->hasOne(Game::className(), ['id' => 'game_id']);
    }

    public function getCountryName()
    {
        if (!$this->country_code) return '';
        $country = Country::findOne($this->country_code);
        if ($country) return $country->country_name;
    }

    public function calculatePointTarget()
    {
        $point = 0;
        foreach (self::TARGET_COLUMNS as $key) {
            $index = array_search($key, array_column(self::QUESTIONS, 'key'));
            if ($index !== false) {
                $point += $this->$key ? self::QUESTIONS[$index]['point_yes'] : self::QUESTIONS[$index]['point_no'];
            }
        }
        return $point;
    }

    public function calculateIsTarget()
    {
        $points = $this->calculatePointTarget();
        return $points >= 3;
    }

    public function calculatePointPotential()
    {
        $point = 0;
        foreach (self::POTENTIAL_COLUMNS as $key) {
            $index = array_search($key, array_column(self::QUESTIONS, 'key'));
            if ($index !== false) {
                $point += $this->$key ? self::QUESTIONS[$index]['point_yes'] : self::QUESTIONS[$index]['point_no'];
            }
        }
        return $point;
    }

    public function calculateIsPotential()
    {
        $points = $this->calculatePointPotential();
        return $points >= 2;
    }

    public static function getQuestionTitle($questionId)
    {
        foreach (self::QUESTIONS as $question) {
            if ($question['key'] === $questionId) {
                return $question['question'];
            }
        }
        return '';
    }
}
