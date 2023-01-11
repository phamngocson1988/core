<?php
namespace common\forms;

use Yii;
use common\models\LeadTracker;
use common\models\LeadTrackerPeriodic;

class CollectLeadTrackerReportForm extends ActionForm
{
    public $id; // lead_tracker_id
    public $month;
    public $year;

    protected $_leadTracker;
    protected $_trackerPeriodic;

    public function rules()
    {
        return [
            [['id', 'month', 'year'], 'trim'],
            [['id', 'month', 'year'], 'required'],
            ['id', 'validateTracker']
        ];
    }

    public function validateTracker($attribute) 
    {
        $tracker = $this->getLeadTracker();
        if (!$tracker) {
            return $this->addError($attribute, 'Customer tracker is not exist');
        }
    }

    public function getLeadTracker() 
    {
        if (!$this->_leadTracker) {
            $this->_leadTracker = LeadTracker::findOne($this->id);
        }
        return $this->_leadTracker;
    }

    public function getTrackerPeriodic()
    {
        if (!$this->_trackerPeriodic) {
            $condition = [
                'month' => "$this->year$this->month",
                'lead_tracker_id' => $this->id,
            ];
            $this->_trackerPeriodic = LeadTrackerPeriodic::findOne($condition);
            if (!$this->_trackerPeriodic) {
                $this->_trackerPeriodic = new LeadTrackerPeriodic($condition);
            }
        }
        return $this->_trackerPeriodic;
    }

    public function run()
    {
        if (!$this->validate()) return false;
        $y = $this->year;
        $m = $this->month;
        $start = "$y-$m-01 00:00:00";
        $end = date("Y-m-t 23:59:59", strtotime($start));

        $tracker = $this->getLeadTracker();
        $periodic = $this->getTrackerPeriodic();
        if ($tracker->target_lead_at && strtotime($end) >= strtotime($tracker->target_lead_at)) {
            $periodic->monthly_status = -1;
        } elseif ($tracker->potential_lead_at && strtotime($end) >= strtotime($tracker->potential_lead_at)) { 
            $periodic->monthly_status = -2;
        }
        $periodic->is_become_potential_lead = $tracker->potential_lead_at && date('Ym', strtotime($tracker->potential_lead_at)) == "$y$m";
        $periodic->is_become_target_lead = $tracker->target_lead_at && date('Ym', strtotime($tracker->target_lead_at)) == "$y$m";
        return $periodic->save();
    }
}