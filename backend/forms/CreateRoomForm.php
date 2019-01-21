<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Room;
use yii\helpers\ArrayHelper;

/**
 * CreatePostForm is the model behind the contact form.
 */
class CreateRoomForm extends Model
{
    public $title;
    public $description;
    public $image_id;
    public $price;
    public $status;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'status'], 'required'],
            [['description', 'image_id', 'price'], 'safe']
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            $room = $this->getRoom();
            try {
                $newId = $room->save();
                $transaction->commit();
                return $newId;
            } catch (Exception $e) {
                $transaction->rollBack();                
                throw $e;
            } catch (\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
        }
    }

    protected function getRoom()
    {
        $room = new Room();
        $room->title = $this->title;
        $room->description = $this->description;
        $room->image_id = $this->image_id;
        $room->price = $this->price;
        $room->status = $this->status;
        $room->created_at = date('Y-m-d');
        $room->created_by = Yii::$app->user->id;
        return $room;
    }
}
