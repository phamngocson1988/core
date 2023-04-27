<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\AffiliateCommissionWithdraw;

class ExecuteAffiliateCommissionWithdrawForm extends Model
{
    public $id;
    public $action;
    public $note;
    private $_request;

    public function rules()
    {
        return [
            [['id', 'action'], 'required'],
            ['note', 'safe'],
        ];
    }

    protected function getRequest() {
        if (!$this->_request) {
          $this->_request = AffiliateCommissionWithdraw::findOne($this->id);
        }
        return $this->_request;
    }

    public function run()
    {
      if (!$this->validate()) return false;
      switch ($this->action) {
        case 'approve':
            return $this->approve();
        case 'disapprove':
            return $this->disapprove();
        case 'execute':
            return $this->execute($this->note);
      }
      return true;
    }

    public function approve()
    {
      $request = $this->getRequest();
      if ($request->isRequest()) {
        $request->status = AffiliateCommissionWithdraw::STATUS_APPROVED;
        $request->approved_by = Yii::$app->user->id;
        $request->approved_at = date('Y-m-d H:i:s');
        $result = $request->save();
        if (!(int)$request->affiliate_account) { // withdraw to kinggems wallet
          $result = $this->execute('Execute automatically');
        }
        return $result;
      } else {
        $this->addError('id', 'Request này không hợp lệ');
        return false;
      }
    }

    public function disapprove()
    {
        $request = $this->getRequest();
        if ($request->isExecuted()) {
          $this->addError('id', 'Request này đã được thực thi');
          return false;
        }
        return $request->delete();
    }

    public function execute($note)
    {
        $request = $this->getRequest();
        if ($request->isApprove()) {
          $request->status = AffiliateCommissionWithdraw::STATUS_EXECUTED;
          $request->executed_by = Yii::$app->user->id;
          $request->executed_at = date('Y-m-d H:i:s');
          $request->note = $note;
          $request->save();
          if (!(int)$request->affiliate_account) { // withdraw to kinggems wallet
            $user = $request->user;
            $user->topup($request->amount, $request->id, sprintf("Topup by affiliate commission request (%s)", $request->id));
          }
          return true;
        } else {
          $this->addError('id', 'Request này chưa được approve');
          return false;
        }
    }
}
