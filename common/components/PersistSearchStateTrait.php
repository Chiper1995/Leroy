<?php
namespace common\components;

use yii\base\Event;
use yii\data\DataProviderInterface;

/**
 * Добавляем функционал по сохранению номера страницы, сортировки и параметров фильтрации при переходе между страницами
 * Class PersistSearchStateTrait
 * @package common\components
 *
 * @mixin ActiveRecord
 */
trait PersistSearchStateTrait
{
    protected function persistState(DataProviderInterface $dataProvider, $scenario = '')
    {
        $this->persistPaging($dataProvider, $scenario);
        $this->persistSorting($dataProvider, $scenario);
        $this->persistFiltering($scenario);
    }

    /**
     * Сохраняем номер страницы
     * @param DataProviderInterface $dataProvider
     * @param string $scenario
     */
    protected function persistPaging(DataProviderInterface $dataProvider, $scenario = '')
    {
        $pageParam = $dataProvider->getPagination()->pageParam;

        if (($page = \Yii::$app->request->getQueryParam($pageParam, false)) !== false) {
            \Yii::$app->session->set(\Yii::$app->controller->action->uniqueId.'_'.$pageParam.'_'.$scenario, $page - 1);
        }
        else {
            $page = \Yii::$app->session->get(\Yii::$app->controller->action->uniqueId.'_'.$pageParam.'_'.$scenario, null);
            $dataProvider->getPagination()->page = $page;
        }
    }

    /**
     * Сохраняем сортировку
     * @param DataProviderInterface $dataProvider
     * @param string $scenario
     */
    protected function persistSorting(DataProviderInterface $dataProvider, $scenario = '')
    {
        $sortParam = $dataProvider->getSort()->sortParam;

        if (($sort = \Yii::$app->request->getQueryParam($sortParam, false)) !== false) {
            \Yii::$app->session->set(\Yii::$app->controller->action->uniqueId.'_'.$sortParam.'_'.$scenario, $sort);
        }
        else {
            if (($sort = \Yii::$app->session->get(\Yii::$app->controller->action->uniqueId.'_'.$sortParam.'_'.$scenario, false)) !== false)
                $_GET[$sortParam] = $sort;
        }
    }

    protected function persistFiltering($scenario = '')
    {
        if (($filter = \Yii::$app->session->get(\Yii::$app->controller->action->uniqueId.'_filter_'.$scenario, false)) !== false)
            $this->setAttributes($filter);

        $this->on(ActiveRecord::EVENT_AFTER_VALIDATE, function(Event $event) use ($scenario) {
            /**@var ActiveRecord $m*/
            $m = $event->sender;
            $attributes = array_values($m->activeAttributes());
            $values = $m->getAttributes($attributes);
            \Yii::$app->session->set(\Yii::$app->controller->action->uniqueId.'_filter_'.$scenario, $values);
        });
    }
}