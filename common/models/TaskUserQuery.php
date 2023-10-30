<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 16.07.2018
 * Time: 22:49
 */

namespace common\models;

use yii\db\ActiveQuery;

class TaskUserQuery extends ActiveQuery
{
    public function active()
    {
        return $this
            ->andWhere(['{{%task_user}}.status' => TaskUser::STATUS_ACTIVE]);
    }

    public function taskOverdue($taskAlias)
    {
        return $this
            ->andWhere($taskAlias.'.deadline IS NOT NULL')
            ->andWhere(['<', $taskAlias.'.deadline', date('Y-m-d')]);
    }

    public function emptyJournal($journalAlias)
    {
        return $this
            ->andWhere($journalAlias.'.id IS NULL');
    }
}