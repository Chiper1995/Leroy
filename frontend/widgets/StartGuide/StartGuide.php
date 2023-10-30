<?php
namespace frontend\widgets\StartGuide;

use common\models\User;
use common\rbac\Rights;
use Yii;
use yii\base\Widget;

class StartGuide extends Widget
{

    public function run()
    {
        if (Yii::$app->user->can(Rights::SHOW_GUIDE)) {
            /**@var User $user */
            $user = Yii::$app->user->identity;

            $secondVisit = true;

            if ((Yii::$app->request->isAjax) and (Yii::$app->request->getBodyParam('guide_viewed', 0) == 1)) {
                $secondVisit = false;
                $user->guide_viewed = 1;
                $user->save(false);
            }
            if ((!$user->guide_viewed) or (!$user->second_visit)) {
                if ($secondVisit and $user->guide_viewed) {
                    $user->second_visit = 1;
                    $user->save(false);
                    return $this->render('userSecondVisit', [
                        'user' => $user,
                    ]);
                }
                return $this->render('index', [
                    'user' => $user,
                ]);
            }
        }
    }
}