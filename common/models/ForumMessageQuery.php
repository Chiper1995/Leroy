<?php
namespace common\models;

use yii\db\ActiveQuery;

class ForumMessageQuery extends ActiveQuery
{
    public function forumThemeMessages($theme_id)
    {
        return $this
            ->andWhere('theme_id = :theme_id', [':theme_id' => $theme_id])
            ->orderBy('created_at');
    }

    public function forumThemeFirstMessage($theme_id)
    {
        return $this
            ->andWhere('theme_id = :theme_id AND is_first = 1', [':theme_id' => $theme_id])
            ->limit(1)
            ->orderBy('created_at');
    }

    public function byUser($user_id)
    {
        return $this
            ->andWhere('user_id = :user_id', [':user_id' => $user_id])
            ->orderBy('created_at');
    }
}