<?php
namespace common\components\actions;

use Closure;
use Yii;
use yii\web\ForbiddenHttpException;

class Action extends \yii\base\Action
{

    /**
     * @var string|Closure Доступ к экшену
     */
    public $access = null;

    public $noAccessMessage = 'У вас нет доступа для выполнения этой операции';

    protected function checkAccess($model)
    {
        // Проверка доступа
        if ($this->access != null) {
            if ($this->access instanceof Closure) {
                if (!call_user_func($this->access, $this, $model)) {
                    throw new ForbiddenHttpException($this->noAccessMessage);
                }
            }
            else if (is_array($this->access)) {
                if (!Yii::$app->user->can(array_shift($this->access), $this->access)) {
                    throw new ForbiddenHttpException($this->noAccessMessage);
                }
            }
            else {
                if (!Yii::$app->user->can($this->access))
                    throw new ForbiddenHttpException($this->noAccessMessage);
            }
        }
    }
}