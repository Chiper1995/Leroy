<?php
namespace frontend\widgets\UserPhoto;

use common\models\User;
use yii\base\Widget;

/**
 * Class UserPhoto
 * @package frontend\widgets\UserPhoto
 *
 * @property User $user
 * @property integer $size
 * @property boolean $showTitle
 * @property boolean $showPosition
 */
class UserPhoto extends Widget
{
    public $user;

    public $size;

    public $showTitle = false;

    public $showPosition = true;

    public function run()
    {
        return $this->render(
            'index',
            [
                'user' => $this->user,
                'size' => $this->size,
                'showTitle' => $this->showTitle,
                'showPosition' => $this->showPosition,
            ]
        );
    }
}