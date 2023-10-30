<?php

namespace console\controllers;

use common\models\User;
use common\models\Journal;
use yii\console\Controller;
use common\components\helpers\DateHelper;
use yii\db\Query;
use common\events\AppEvents;
use common\events\custom\LastVisitMailEvent as Event;
use common\components\UserEmailSender;

class MailController extends Controller
{

    //делает сразу все
    public function actionLastVisits()
    {
        $this->actionLastVisitsUsers();
        $this->actionLastVisitsCurators();
        $this->actionLastVisitsAdmins();
    }


    //отправляет письма юзерам
    public function actionLastVisitsUsers()
    {
        $users = User::find()
            ->where(['role' => User::ROLE_FAMILY])
            ->andWhere(['<=', 'last_visit', DateHelper::dateEarlier(20)])
            ->andWhere(['=', 'visit_notified', 0])
            ->all();

        foreach ($users as $user) {
            $data = [
                'mailView' => 'lastVisitUser',
                'mailRecipient' => $user->email,
                'mailTitle' => 'Возвращайтесь в «Семьи Леруа Мерлен»',
                'mailOptions' => [],
            ];

            \Yii::$app->trigger(AppEvents::EVENT_SEND_MAIL_LAST_VISITS, new Event($data));

            $user->visit_notified = 1;
            $user->save();
        }

    }


    //отправляет письма админам
    public function actionLastVisitsAdmins()
    {
        $curators = User::find()
            ->select('fio')
            ->where(['role' => User::ROLE_SHOP])
            ->andWhere(['<=', 'last_visit', DateHelper::dateEarlier(14)])
            ->andWhere(['<=', 'visit_notified', 2])
            ->asArray()
            ->all();

        $admins = User::find()
            ->where(['role' => User::ROLE_ADMINISTRATOR])
            ->all();

        foreach ($admins as $admin) {
            $data = [
                'mailView' => 'lastVisitAdmin',
                'mailRecipient' => $admin->email,
                'mailTitle' => 'Кураторы не выходят на платформу',
                'mailOptions' => ['curators' => $curators],
            ];

            \Yii::$app->trigger(AppEvents::EVENT_SEND_MAIL_LAST_VISITS, new Event($data));
        }

        //меняем статусы кураторов
        $this->actionCuratorChangeStatus();

    }


    //отправляет письма кураторам, не заходившим 7 дней
    public function actionLastVisitsCurators()
    {
        $curators = User::find()
            ->where(['role' => User::ROLE_SHOP])
            ->andWhere(['<=', 'last_visit', DateHelper::dateEarlier(7)])
            ->andWhere(['=', 'visit_notified', 0])
            ->all();

        foreach ($curators as $curator) {
            $count = $this->actionCuratorGetUnverified($curator);

            $data = [
                'mailView' => 'lastVisitCurator',
                'mailRecipient' => $curator->email,
                'mailTitle' => 'Возвращайтесь в «Семьи Леруа Мерлен»',
                'mailOptions' => ['posts' => $count],
            ];

            \Yii::$app->trigger(AppEvents::EVENT_SEND_MAIL_LAST_VISITS, new Event($data));

            $curator->visit_notified = 1;
            $curator->save();
        }
    }

    //выводит и возвращает кол-во непроверенных записией определенного куратора
    public function actionCuratorGetUnverified($curator)
    {
        $usersInCitiesQuery = (new Query())
            ->select('user_id')
            ->from('{{%user_city}} uc')
            ->where(['uc.city_id' => $curator->getCities()->select('id')->column()]);

        $count = Journal::find()
            ->where(['status' => Journal::STATUS_ON_CHECK])
            ->andWhere(['user_id' => $usersInCitiesQuery])
            ->count();

        echo 'Куратор ' . $curator->fio . $this->getGrammarStr($count);

        return $count;
    }


    //меняет статус кураторов, не заходивших 14 дней
    private function actionCuratorChangeStatus()
    {
        $curators = User::find()
            ->where(['role' => User::ROLE_SHOP])
            ->andWhere(['<=', 'last_visit', DateHelper::dateEarlier(14)])
            ->andWhere(['<=', 'visit_notified', 2])
            ->all();

        foreach ($curators as $curator) {
            $curator->visit_notified = 2;
            $curator->save();
        }
    }


    //меняет окончание слова в зависимости от числа
    private function getGrammarStr($count)
    {
        $str = ' непроверенных записей.';

        if (substr($count, -1) == '1'){
            $str = ' непроверенную запись.';
        }

        if (substr($count, -2) == '11'){
            $str = ' непроверенных записей.';
        }

        return ' имеет ' . $count . $str . "\n";
    }
}
