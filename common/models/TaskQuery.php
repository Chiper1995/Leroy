<?php
namespace common\models;

use yii\db\ActiveQuery;

class TaskQuery extends ActiveQuery
{
    public function allTasks()
    {
        return $this;
    }

    public function myTasks()
    {
        return
            TaskUser::find()
                ->joinWith(['task', 'journal'])
                ->andWhere('{{%task_user}}.user_id = :user_id', [':user_id' => \Yii::$app->user->id])
                ->andWhere(['=', '{{%task_user}}.status', TaskUser::STATUS_ACTIVE]);
    }

    public function overdue()
    {
        return $this
            ->andWhere('deadline IS NOT NULL')
            ->andWhere(['<', 'deadline', date('Y-m-d')]);
    }

}