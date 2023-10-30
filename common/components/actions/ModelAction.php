<?php
namespace common\components\actions;

use Yii;
use common\components\controllers\IModelController;

/**
 * Class ModelAction
 * @package common\actions
 *
 *
 * @property string $modelClass
 * @property string $modelScenario
 */
class ModelAction extends Action
{
    private $_modelClass;

    /**
     * @return string
     * @throws \ErrorException
     */
    public function getModelClass()
    {
        if($this->_modelClass === null)
        {
            if($this->controller instanceof IModelController && ($modelClass = $this->controller->getModelClass()))
                $this->_modelClass = $modelClass;
            else
                throw new \ErrorException('Model class must be setted');
        }

        return $this->_modelClass;
    }

    /**
     * @param $modelClass string
     */
    public function setModelClass($modelClass)
    {
        $this->_modelClass = $modelClass;
    }

    private $_modelScenario;

    /**
     * @return string
     */
    public function getModelScenario()
    {
        return $this->_modelScenario;
    }

    /**
     * @param $modelScenario string
     */
    public function setModelScenario($modelScenario)
    {
        $this->_modelScenario = $modelScenario;
    }
}