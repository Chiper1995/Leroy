<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 07.09.2018
 * Time: 0:19
 */

namespace frontend\actions;

use common\components\actions\UploadImageAction;
use common\models\Journal;
use common\models\JournalPhoto;
use common\rbac\Rights;
use Yii;
use yii\base\ErrorException;

class JournalUploadPhotoAction extends UploadImageAction
{
    protected function getUploadPath($imageModel)
    {
        return $imageModel::getPath();
    }

    protected function afterUpload($uploader, $result)
    {
        if ((($journalId = Yii::$app->request->post('journalId')) != null)
            && (($journal = Journal::findOne($journalId))) instanceof Journal)
        {
            $photoModel = new JournalPhoto();
            $photoModel->journal_id = $journal->id;
            $photoModel->photo = $uploader->getUploadName();

            if ($journal->status == Journal::STATUS_PUBLISHED) {
                if (
                    (Yii::$app->user->can(Rights::SHOW_JOURNAL_ON_CHECK_NOTIFICATION, ['journal'=>$journal]))
                    || (Yii::$app->user->can(Rights::SHOW_JOURNAL_BY_TASK_ON_CHECK_NOTIFICATION, ['journal'=>$journal]))
                ) {
                    $photoModel->status = JournalPhoto::STATUS_PUBLISHED;
                } else {
                    $photoModel->status = JournalPhoto::STATUS_ON_CHECK;
                }
            } else {
                $photoModel->status = $journal->status;
            }

            if ($photoModel->save()) {
                $result['id'] = $photoModel->id;
                $result['fileurl'] = $photoModel->getPhotoThumb(253, 190);
                $result['fullfileurl'] = $photoModel->getPhotoUrl();
                $result['filename'] = $photoModel->photo;
                $result['description'] = (string)$photoModel->description;
                return $result;
            }
            else {
                throw new ErrorException('Ошибка при загрузке изображения [3]');
            }
        }
        throw new ErrorException('Ошибка при загрузке изображения [2]');
    }
}