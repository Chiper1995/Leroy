<?php
namespace frontend\widgets\GiveGiftLink;

use frontend\assets\ModalPjaxAsset;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\JqueryAsset;
use yii\web\View;

class GiveGiftLink extends Widget
{
    public $options = [];

    public $pluginOptions = [];

    public $selector;

    public $url;

    public $journalId;

    public function init()
    {
        parent::init();

        if (!isset($this->options['id']) && !isset($this->selector)) {
            $this->options['id'] = $this->getId();
        }

        $class = ArrayHelper::getValue($this->options, 'class');
        $this->options['class'] = 'give-gift-btn';
        if (isset($class))
            Html::addCssClass($this->options, $class);

        $this->options['data-journal-id'] = $this->journalId;

        $this->pluginOptions = array_merge(static::defaultPluginOptions(), $this->pluginOptions);

        $this->pluginOptions['selector'] = $this->selector;
    }

    public function run()
    {
        $this->renderWidget();
        $this->registerClientScript();
    }

    protected function renderWidget()
    {
        echo Html::a(
            Html::icon('gift').'<span class="title">Подарить баллы</span>',
            $this->url,
            $this->options
        );
    }

    public static function defaultPluginOptions()
    {
        return [
            'modalPjaxId' => 'give-gift-form-pjax',
            'modalTitle' => 'Подарить свои баллы семье',
        ];
    }

    protected function registerClientScript()
    {
        $view = $this->view;

        ModalPjaxAsset::register($view);
        GiveGiftLinkAsset::register($view);

        $options = Json::encode($this->pluginOptions);

        if (isset($this->pluginOptions['selector']))
            $view->registerJs('$("body").giveGiftLink('.$options.')');
        else
            $view->registerJs('$("#'.$this->options['id'].'").giveGiftLink('.$options.')');
    }

    /**
     * @param View $view
     * @param array $pluginOptions
     * @throws InvalidConfigException
     */
    public static function registerClientScriptForList($view, $pluginOptions = [])
    {
        $pluginOptions = array_merge(static::defaultPluginOptions(), $pluginOptions);

        if (!isset($pluginOptions['selector']))
            throw new InvalidConfigException('Selector must be set');

        ModalPjaxAsset::register($view);
        GiveGiftLinkAsset::register($view);

        $options = Json::encode($pluginOptions);
        $view->registerJs('$("body").giveGiftLink('.$options.')');
    }
}