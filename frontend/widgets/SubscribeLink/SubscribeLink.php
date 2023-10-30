<?php

namespace frontend\widgets\SubscribeLink;

use yii\base\Widget;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\web\JqueryAsset;
use yii\web\View;

class SubscribeLink extends Widget
{
    public $options = [];

    public $selector;

    public $subscribedIt = false;

    public $url;

    public function init()
    {
        parent::init();

        if (!isset($this->options['id']) && !isset($this->selector)) {
            $this->options['id'] = $this->getId();
        }

        $class = ArrayHelper::getValue($this->options, 'class');
        $this->options['class'] = 'subscribe-link';
        if (isset($class))
            Html::addCssClass($this->options, $class);

        if ($this->subscribedIt)
            Html::addCssClass($this->options, 'subscribed-it');

        if (!isset($this->options['title'])){
            if ($this->subscribedIt)
                $this->options['title'] = 'Отписаться';
            else
                $this->options['title'] = 'Подписаться';

        }

        $this->options['data']['toggle'] = 'tooltip';
        $this->options['data']['placement'] = 'top';
    }

    public function run()
    {
        $this->renderWidget();
        $this->registerClientScript();
    }

    protected function renderWidget()
    {
        echo Html::a(
            'подписка',
            $this->url,
            $this->options
        );
    }

    protected function registerClientScript()
    {
        $view = $this->view;

        $view->registerJsFile('@web/js/subscribe-link.min.js', ['position' => View::POS_END, 'depends'=>[JqueryAsset::className()]]);

        if (isset($this->selector))
            $view->registerJs('$("body").subscribeLink({selector: "'.$this->selector.'"})');
        else
            $view->registerJs('$("#'.$this->options['id'].'").subscribeLink()');
    }
}