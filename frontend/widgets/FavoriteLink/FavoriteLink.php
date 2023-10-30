<?php

namespace frontend\widgets\FavoriteLink;

use yii\base\Widget;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\web\JqueryAsset;
use yii\web\View;

class FavoriteLink extends Widget
{
    public $options = [];

    public $selector;

    public $favoriteIt = false;

    public $url;

    public $removeFavorite;

    public function init()
    {
        parent::init();

        if (!isset($this->options['id']) && !isset($this->selector)) {
            $this->options['id'] = $this->getId();
        }

        $class = ArrayHelper::getValue($this->options, 'class');
        $this->options['class'] = 'favorite-link';
        if ($this->removeFavorite){
            $this->options['class'] .= ' js-remove-favorite';
        }

        if (isset($class))
            Html::addCssClass($this->options, $class);

        if (!isset($this->options['title']))
            $this->options['title'] = 'Избранное';

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
            '<span>'.Html::icon($this->favoriteIt ? 'star' : 'star-empty'),
            $this->url,
            $this->options);
    }

    protected function registerClientScript()
    {
        $view = $this->view;

        $view->registerJsFile('@web/js/favorite-link.min.js', ['position' => View::POS_END, 'depends'=>[JqueryAsset::className()]]);

        if (isset($this->selector)) {
            $view->registerJs('$("body").favoriteLink({selector: "' . $this->selector . '"})');
        }
        else{
            $view->registerJs('$("#'.$this->options['id'].'").favoriteLink()');
        }

    }
}