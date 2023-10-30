<?php


namespace frontend\widgets\StatusButtonsFilter;


use yii\base\Widget;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class StatusButtonsFilterJournal extends Widget
{
    public $selectedStatus;

    public $statusList = [];

    public $route;

    public $routeParams = [];

    public $paramName = 'status';

    public $cssClass = 'status-filter';

    public $btnCssClass = 'btn btn-default';

    public function run()
    {
        $buttons = [];
        $items = [];
        $this->statusList = ArrayHelper::merge([null=>'Лента подписок'], $this->statusList);
        $selectedText = $this->statusList[null];
        foreach ($this->statusList as $st => $stn) {
            $url = Url::toRoute(ArrayHelper::merge([$this->route, $this->paramName=>$st], $this->routeParams));
            $buttons[] = Html::a($stn, $url, ['class'=>$this->btnCssClass.(($this->selectedStatus == $st) ? ' active' : ''),]);
            $items[] = ['label'=>$stn, 'url'=>$url];
            if ($this->selectedStatus == $st)
                $selectedText = $stn;
        }

        return $this->render('index', [
            'buttons'=>$buttons,
            'items'=>$items,
            'selectedText'=>$selectedText,
            'cssClass'=>$this->cssClass,
            'btnCssClass'=>$this->btnCssClass,
        ]);
    }
}