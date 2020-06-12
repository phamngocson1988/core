<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Game;
use backend\models\GamePriceLog;
use backend\models\User;
use yii\helpers\ArrayHelper;

class UpdateGamePriceForm extends Model
{
    public $id;
    public $price1;
    public $price2;
    public $price3;
    public $price_remark;

    protected $_game;

    public function rules()
    {
        return [
            [['id'], 'required'],
            [['price1', 'price2', 'price3', 'price_remark'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'price1' => 'Giá nhà cung cấp 1',
            'price2' => 'Giá nhà cung cấp 2',
            'price3' => 'Giá nhà cung cấp 3',
            'price_remark' => 'Price Remark'
        ];
    }

    public function save()
    {
        $transaction = Yii::$app->db->beginTransaction();
        $model = $this->getGame();
        try {
            $model->on(Game::EVENT_AFTER_UPDATE, function($event) {
                $game = $event->sender; //game
                $oldGame = clone $game;
                $oldAttributes = $event->changedAttributes;
                $oldGame->price1 = ArrayHelper::getValue($oldAttributes, 'price1');
                $oldGame->price2 = ArrayHelper::getValue($oldAttributes, 'price2');
                $oldGame->price3 = ArrayHelper::getValue($oldAttributes, 'price3');
                $setting = Yii::$app->settings;
                $config = [
                    'managing_cost_rate' => $setting->get('ApplicationSettingForm', 'managing_cost_rate', 0),
                    'investing_cost_rate' => $setting->get('ApplicationSettingForm', 'investing_cost_rate', 0),
                    'desired_profit' => $setting->get('ApplicationSettingForm', 'desired_profit', 0),
                    'reseller_desired_profit' => $setting->get('ApplicationSettingForm', 'reseller_desired_profit', 0),
                ];
                $newPrice = $game->getPrice();
                $oldPrice = $oldGame->getPrice();
                $attrs = [
                    'old_price' => $oldPrice,
                    'new_price' => $newPrice,
                    'old_price_1' => $oldGame->price1,
                    'old_price_2' => $oldGame->price2,
                    'old_price_3' => $oldGame->price3,
                    'new_price_1' => $game->price1,
                    'new_price_2' => $game->price2,
                    'new_price_3' => $game->price3,
                    'old_reseller_1' => $oldGame->getResellerPrice(User::RESELLER_LEVEL_1),
                    'new_reseller_1' => $game->getResellerPrice(User::RESELLER_LEVEL_1),
                    'old_reseller_2' => $oldGame->getResellerPrice(User::RESELLER_LEVEL_2),
                    'new_reseller_2' => $game->getResellerPrice(User::RESELLER_LEVEL_2),
                    'old_reseller_3' => $oldGame->getResellerPrice(User::RESELLER_LEVEL_3),
                    'new_reseller_3' => $game->getResellerPrice(User::RESELLER_LEVEL_3),
                ];
                Yii::error($attrs, 'actionUpdatePrice attrs');
                $log = new GamePriceLog();
                foreach ($attrs as $key => $value) {
                    $log->$key = $value;
                }
                $log->game_id = $game->id;
                $log->config = json_encode(array_merge($event->changedAttributes, $config));
                $log->save();

                // Send mail to saler
                $salerIds = Yii::$app->authManager->getUserIdsByRole('saler');
                $saleManagerIds = Yii::$app->authManager->getUserIdsByRole('sale_manager');
                $salerTeamIds = array_merge($salerIds, $saleManagerIds);
                $salerTeamIds = array_unique($salerTeamIds);
                $salerTeams = User::findAll($salerTeamIds);
                $salerEmails = ArrayHelper::getColumn($salerTeams, 'email');
                $admin = Yii::$app->params['email_admin'];
                $siteName = Yii::$app->name;
                Yii::$app->mailer->compose('notify_updating_game_price', [
                    'game' => $game,
                    'changes' => $attrs
                ])
                ->setTo($salerEmails)
                ->setFrom([$admin => $siteName])
                ->setSubject(sprintf("KINGGEMS.US - Game %s have just been updated its price", $game->title))
                ->setTextBody(sprintf("KINGGEMS.US - Game %s have just been updated its price", $game->title))
                ->send();
            });
            $model->price1 = $this->price1;
            $model->price2 = $this->price2;
            $model->price3 = $this->price3;
            $model->price_remark = $this->price_remark;
            $result = $model->save();

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

    public function getGame()
    {
        if (!$this->_game) {
            $this->_game = Game::findOne($this->id);
        }
        return $this->_game;
    }

    public function loadData()
    {
        $game = $this->getGame();
        $this->price1 = $game->price1;
        $this->price2 = $game->price2;
        $this->price3 = $game->price3;
        $this->price_remark = $game->price_remark;
    }
}
