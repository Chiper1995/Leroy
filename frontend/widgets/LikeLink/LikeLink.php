<?php

namespace frontend\widgets\LikeLink;

use yii\base\Widget;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\web\JqueryAsset;
use yii\web\View;

class LikeLink extends Widget
{
    public $options = [];

    public $selector;

    public $likeCount = 0;

    public $likeIt = false;

    public $url;

    public function init()
    {
        parent::init();

        if (!isset($this->options['id']) && !isset($this->selector)) {
            $this->options['id'] = $this->getId();
        }

        $class = ArrayHelper::getValue($this->options, 'class');
        $this->options['class'] = 'like-link';
        if (isset($class))
            Html::addCssClass($this->options, $class);

        if (!isset($this->options['title']))
            $this->options['title'] = 'Нравится';

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
            '<span class="like-count">'.$this->likeCount.'</span>'.Html::icon($this->likeIt ? 'heart' : 'heart-empty'),
            $this->url,
            $this->options);
    }

    protected function registerClientScript()
    {
        $view = $this->view;

        $view->registerJsFile('@web/js/like-link.min.js', ['position' => View::POS_END, 'depends'=>[JqueryAsset::className()]]);

        if (isset($this->selector))
            $view->registerJs('$("body").likeLink({selector: "'.$this->selector.'"})');
        else
            $view->registerJs('$("#'.$this->options['id'].'").likeLink()');
    }
}