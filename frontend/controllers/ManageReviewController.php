<?php
namespace frontend\controllers;

use Yii;
use frontend\models\OperatorReview;

class ManageReviewController extends ManageController
{
    protected $_actions = ['index', 'list', 'reply'];
    // , 'reply-review', 'reply-complain', 'review', 'list-review', 'complain', 'my-complain', 'list-complain', 'list-my-complain', 'detail-complain'

    public function actionIndex()
    {
        $request = Yii::$app->request;
        $operator = $this->getOperator();
        $user = Yii::$app->user->identity;
        if (!$request->getIsAjax()) {
            return $this->render('index', [
                'operator' => $operator,
                'user' => $user,
            ]);
        } else {
            $offset = $request->get('offset', 0);
            $limit = $request->get('limit', 10);

            $command = OperatorReview::find()->where(['operator_id' => $operator->id])->offset($offset)->limit($limit);
            if ($request->get('sort') == 'date') {
                $type = $request->get('type', 'asc') == 'asc' ? SORT_ASC : SORT_DESC;
                $command->orderBy(['created_at' => $type]);
            }
            $status = $request->get('status');
            if ($status) {
                $not = $status == 'responded' ? 'NOT' : '';
                $command->andWhere(["IS $not", "reply", null]);
            }

            $models = $command->all();
            $total = $command->count();
            $html = $this->renderPartial('list', ['models' => $models]);
            return $this->asJson(['status' => true, 'data' => [
                'items' => $html,
                'total' => $total
            ]]);
        }

    }

    public function actionReply()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);

        $model = new \frontend\forms\ReplyOperatorReviewForm([
            'user_id' => Yii::$app->user->id,
            'id' => $request->get('review_id')
        ]);
        if ($model->load($request->post()) && $model->validate() && $model->reply()) {
            return json_encode(['status' => true, 'data' => ['message' => Yii::t('app', 'reply_review_success')]]);
        } else {
            $message = $model->getErrorSummary(true);
            $message = reset($message);
            return json_encode(['status' => false, 'errors' => $message]);
        }
    }

}