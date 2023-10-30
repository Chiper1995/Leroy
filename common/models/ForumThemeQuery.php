<?php
namespace common\models;

use yii\db\ActiveQuery;

class ForumThemeQuery extends ActiveQuery
{
    public function forumThemes($parent_id = null)
    {
        if ($parent_id == null)
            $this->andWhere('parent_id IS NULL');
        else
            $this->andWhere('parent_id = :parent_id', [':parent_id' => $parent_id]);

        return $this->orderBy('name');
    }

    public function forumMessagesThemes($parent_id = null)
    {
        if ($parent_id == null)
            $this->andWhere('parent_id IS NULL');
        else
            $this->andWhere('parent_id = :parent_id', [':parent_id' => $parent_id]);

        $this->andWhere('is_messages_theme = 1');

        return $this->orderBy('name');
    }
}