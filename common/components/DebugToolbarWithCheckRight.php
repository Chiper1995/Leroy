<?php
namespace common\components;

use common\rbac\Rights;
use Yii;
use yii\debug\Module as DebugModule;

class DebugToolbarWithCheckRight extends DebugModule
{
    private $_basePath;

    protected function checkAccess()
    {
        // Проверка права доступа
        if (!Yii::$app->user->can(Rights::SHOW_DEBUG_TOOLBAR)) {
            return false;
        }

        return parent::checkAccess();
    }

    /**
     * Returns the root directory of the module.
     * It defaults to the directory containing the module class file.
     * @return string the root directory of the module.
     */
    public function getBasePath()
    {
        if ($this->_basePath === null) {
            $class = new \ReflectionClass(new DebugModule('debug'));
            $this->_basePath = dirname($class->getFileName());
        }

        return $this->_basePath;
    }

}