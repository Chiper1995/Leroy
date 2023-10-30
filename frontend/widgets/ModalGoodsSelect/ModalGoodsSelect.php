<?php
namespace frontend\widgets\ModalGoodsSelect;

use Yii;
use yii\base\Widget;

/**
 * Class ModalGoodsSelect
 * @package frontend\widgets\ModalGoodsSelect
 *
 * @property integer $id
 * @property string $onNodeSelected
 * @property string $onNodeUnselected
 */
class ModalGoodsSelect extends Widget
{
    public $id;

    public $onNodeSelected;

    public $onNodeUnselected;

    public function run()
    {
        return $this->render('index', [
            'id' => $this->id,
            'onNodeSelected' => $this->onNodeSelected,
            'onNodeUnselected' => $this->onNodeUnselected,
        ]);
    }
}