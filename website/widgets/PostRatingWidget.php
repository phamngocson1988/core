<?php
namespace website\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;

class PostRatingWidget extends Widget
{
    public $post_id;
    public $sectionId = 'post-rating';

    /**PostRating */
    protected $_userRating;
    /**int */
    protected $_total_rating = 0;
    /**float */
    protected $_average = 0;
    
    public function run()
    {
        if (!$this->post_id) return;
        $total = $this->getTotalRating();
        $stars = $this->getUserStar();
        $average = $this->getAverage();

        if ($this->canRating()) {
            $this->registerClientScript();
        }
        return $this->render('post-rating', [
            'total' => number_format($total),
            'stars' => $stars,
            'average' => number_format($average, 1),
            'sectionId' => $this->sectionId
        ]);
    }

    protected function getUserRating()
    {
        if (!$this->_userRating) {
            $this->_userRating = \common\models\PostRating::find()->where([
                'post_id' => $this->post_id,
                'user_id' => Yii::$app->user->id
            ])->one();
        }
        return $this->_userRating;
    }

    protected function getUserStar()
    {
        if (Yii::$app->user->isGuest) return 0;
        $userRating = $this->getUserRating();
        return $userRating ? (int)$userRating->rating : 0;
    }

    protected function canRating()
    {
        if (Yii::$app->user->isGuest) return true;
        $userRating = $this->getUserRating();
        return !$userRating;
    }

    protected function getTotalRating()
    {
        if (!$this->_total_rating) {
            $this->_total_rating = \common\models\PostRating::find()->where(['post_id' => $this->post_id])->count();
        }
        return (int)$this->_total_rating;
    }

    protected function getAverage()
    {
        if (!$this->_average) {
            $this->_average = \common\models\PostRating::find()->where(['post_id' => $this->post_id])->average('rating');
        }
        return $this->_average;
    }

    protected function getScriptCode()
    {
        $ratingUrl = Url::to(['post/rating', 'id' => $this->post_id]);
        $sectionId = $this->sectionId;
        return "
        var ratingStarsSelector = '#$sectionId #post-stars';
        var starItemSelector = ratingStarsSelector + '>li';
        var userRatingSelector = '#$sectionId #user-rating';
        var totalRatingSelector = '#$sectionId #total-rating';
        var averageRatingSelector = '#$sectionId #average-rating';
        $(starItemSelector).on('click', function() {
            $(starItemSelector).removeClass('selected');
            var rating = parseInt($(this).data('value'));
            selectStar(rating);
            
            $.ajax({
              url: '$ratingUrl',
              type: 'post',
              dataType : 'json',
              data: { rating },
              success: function (result, textStatus, jqXHR) {
                  console.log(result);
                  $(totalRatingSelector).html(result.total);
                  $(averageRatingSelector).html(result.average);
                  $(starItemSelector).unbind();
              },
          });
        });
        
        function selectStar(val) {
            $.each($(starItemSelector), function( index, value ) {
                if (index + 1 <= val) {
                    $(value).addClass('selected');
                }
            });
            $(userRatingSelector).html(val);
        }
";
    }

    protected function registerClientScript()
    {
        $js = [];
        $view = $this->getView();
        $js = $this->getScriptCode();
        $view->registerJs($js);
    }

}