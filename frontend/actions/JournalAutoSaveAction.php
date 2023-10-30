<?php
namespace frontend\actions;

use common\models\Journal;
use ReflectionClass;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class JournalAutoSaveAction extends JournalUpdateAction
{
    /**
     * @var string view for action
     */
    public $view = null; // Not needed

	/**
	 * @param integer $id
	 * @return array|string|Response
	 * @throws NotFoundHttpException
	 * @throws \ErrorException
	 * @throws \ReflectionException
	 * @throws \yii\web\ForbiddenHttpException
	 */
    public function run($id)
    {
        if (Yii::$app->request->isAjax) {
            /**@var Journal $model*/
            $model = (new ReflectionClass($this->getModelClass()))->newInstance();
            if (($model = $model::findOne($id)) === null) {
                throw new NotFoundHttpException("{$this->getModelClass()} not found");
            }

            $model->setScenario($this->modelScenario);

            // Проверка доступа
            $this->checkAccess($model);

            $result = [];

            $versionToken = Yii::$app->request->post()['Journal']['version_token'];
            if ($versionToken != $model->version_token) {
                $result['result'] = 'error';
                $result['msg'] = 'Документ устарел';
            } else {
                $model->updateVersionToken();

                if ($model->load(Yii::$app->request->post())) {
                    if ($this->processPostData($model)) {
                        if ($this->savePostData($model)) {
                            $result['result'] = 'ok';
                        }
                        else {
                            $result['result'] = 'error';
                            $result['msg'] = 'Возникла ошибка при сохранении';
                        }
                    }
                    else {
                        $result['result'] = 'error';
                        $result['msg'] = 'Проверьте правильность заполнения полей';
                    }
                }
                $result['version_token'] = $model->version_token;
            }

            Yii::$app->response->format = Response::FORMAT_JSON;
            return $result;
        }
        throw new NotFoundHttpException("Page not found");
    }
}