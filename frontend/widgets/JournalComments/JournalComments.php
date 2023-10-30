<?php
namespace frontend\widgets\JournalComments;

use common\models\Journal;
use common\models\JournalComment;
use common\rbac\Rights;
use Yii;
use yii\base\Widget;
use yii\web\ForbiddenHttpException;

/**
 * Class JournalComments
 * @package frontend\widgets\JournalComments
 *
 * @property Journal $journal
 */
class JournalComments extends Widget
{
    public $journal;

	public $noAccessMessage = 'У вас нет доступа для выполнения этой операции';

	/**
	 * @return string
	 * @throws ForbiddenHttpException
	 * @throws \Exception
	 * @throws \yii\db\Exception
	 * @throws \yii\db\StaleObjectException
	 */
	public function run()
    {
        $model = new JournalComment();
        $model->setScenario('create');
        $createdCommentId = null;

        if (Yii::$app->request->post('add_comment') !== null) {
        	if (Yii::$app->user->can(Rights::ADD_COMMENT, ['journal' => $this->journal])) {
				if ($model->load(Yii::$app->request->post())) {

					if ($model->parent instanceof JournalComment) {
						$model->appendTo($model->parent);
					}
					else {
						$model->makeRoot();
					}

					$model->journal_id = $this->journal->id;
					$model->user_id = Yii::$app->user->id;

					if ($model->save()) {
						$createdCommentId = $model->id;
						$model = new JournalComment();
						$model->setScenario('create');
					}
					else {

					}
				}
			}
        	else {
        		throw new ForbiddenHttpException($this->noAccessMessage);
			}
        }

        else if (Yii::$app->request->post('edit_comment') !== null) {
            if ($commentId = Yii::$app->request->post('edit_comment', false)) {
                $comment = JournalComment::findOne($commentId);
                if (Yii::$app->user->can(Rights::EDIT_COMMENT, ['comment'=>$comment])) {
                    $comment->content = Yii::$app->request->post('comment_text', false);
                    $comment->save();
                }
            }
        }

        else if (Yii::$app->request->post('del_comment') !== null) {
            if ($commentId = Yii::$app->request->post('del_comment', false)) {
                $comment = JournalComment::findOne($commentId);
                if (Yii::$app->user->can(Rights::DELETE_COMMENT, ['comment'=>$comment])) {
					if (($comment->getChildren()->count() > 0) || ($comment->isRoot()))
						$comment->deleteWithChildren();
					else
						$comment->delete();
				}
            }
        }

        return $this->render('index', [
            'journal'=>$this->journal,
            'model'=>$model,
            'createdCommentId'=>$createdCommentId,
        ]);
    }
}
