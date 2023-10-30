<?php
namespace frontend\widgets\PopupFavorite;

use Yii;
use yii\base\Widget;
use common\models\UserPleaAddFavorite;

class PopupFavorite extends Widget
{
    const COUNT_MODAL_VIEW = 2;

    public function run()
    {
        if (empty($identity = Yii::$app->user->identity)) return;

        $favorite = $this->getFavorite($identity);

        if (!$this->isNeedShowModal($favorite->count)) return;

        Yii::$app->session->set('modalFavorite', 'true');
        $favorite->count++;
        $favorite->save();

        return $this->render('index', [
            'message' => "Нажмите <b style='color:red;'>'Ctrl + D'</b> для добавления страницы в закладки",
        ]);

    }

    protected function getFavorite($identity)
    {
        if (empty($favorite = $identity->pleaAddFavorite)) {
            $favorite = new UserPleaAddFavorite();
            $favorite->count = 0;
            $favorite->link('user', $identity);
        }

        return $favorite;
    }

    protected function isNeedShowModal($count)
    {
        return empty(Yii::$app->session->get('modalFavorite')) && $count < self::COUNT_MODAL_VIEW;
    }
}


