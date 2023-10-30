<?php
/**
 * Created by PhpStorm.
 * User: arshatilov
 * Date: 02.07.2018
 * Time: 10:51
 */

namespace frontend\actions;

use common\models\Journal;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;
use ReflectionClass;

class JournalViewPdfAction extends JournalAction
{
    /**
     * @var string view for action
     */
    public $view = 'viewPdf';

    /**
     * @var string scenario for models
     */
    public $modelScenario = 'view';

    /**
     * @var string|array url for return after success update
     */
    public $returnUrl = 'index';

    public $withImages; //флаг вклчения/отключения загрузки изображений

    public function beforeRun()
    {
        $this->withImages = null;
        if (Yii::$app->request->get('withImages') !== null) {
            $this->withImages = Yii::$app->request->get('withImages');
        }

        return parent::beforeRun();
    }

    /**
     * @param $id
     * @return array|string|Response
     * @throws NotFoundHttpException
     */
    public function run($ids)
    {
        if (Yii::$app->request->post('cancel') !== null) {
            return $this->controller->redirect([$this->returnUrl]);
        }
        $ids = explode(';', $ids);
        /**@var Journal $model*/
        $model = (new ReflectionClass($this->getModelClass()))->newInstance();
        if (($model = $model::findOne($ids[0])) === null) {
            throw new NotFoundHttpException("{$this->getModelClass()} not found");
        }

        $model->setScenario($this->modelScenario);

        // Проверка доступа
        $this->checkAccess($model);

        $tempFile = Yii::$app->html2pdf
        ->render($this->view, [
            'model' => $model,
            'withImages' => $this->withImages,
        ]);
        // ->saveAs(Yii::getAlias('@webroot').'/pdf/render/output'. $ids[0] .'.pdf');
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        Yii::$app->response->sendFile($tempFile->name, 'task'. $ids[0] .'.pdf');
    }

}
