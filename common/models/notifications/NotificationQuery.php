<?php
namespace common\models\notifications;

use yii\db\ActiveQuery;
use yii\db\Expression;

class NotificationQuery extends ActiveQuery
{
    public $type;

    public function prepare($builder)
    {
        if ($this->type !== null) {
            $this->andWhere(['type' => $this->type]);
        }
        return parent::prepare($builder);
    }

    public function grouped() {
        return $this
            ->select([
                'MAX({{%notification}}.id) id',
                '{{%notification}}.type',
                '{{%notification}}.init_user_id',
                'MAX({{%notification}}.updated_at) updated_at',
                '{{%notification}}.journal_id',
                '{{%notification}}.task_id',
                '{{%notification}}.visit_id',
                'MAX({{%notification}}.journal_comment_id) journal_comment_id',
                'MAX({{%notification}}.dialog_message_id) dialog_message_id',
                'COUNT(*) count',
                'COUNT(DISTINCT jc.user_id) countCommentUser'
            ])
            ->withCommentsAndDialog()
            ->groupBy([
                '{{%notification}}.type',
                '{{%notification}}.init_user_id',
                '{{%notification}}.journal_id',
                '{{%notification}}.task_id',
                '{{%notification}}.visit_id',
                'jc.journal_id',
                'dm.dialog_id',
                '{{%notification}}.object_id'
            ]);
    }


    public function withCommentsAndDialog() {
        return $this
            ->leftJoin('{{%journal_comment}} jc', ['jc.id' => new Expression('{{%notification}}.journal_comment_id')])
            ->leftJoin('{{%dialog_message}} dm', ['dm.id' => new Expression('{{%notification}}.dialog_message_id')])
			->andWhere(
				[
					'OR',
					'{{%notification}}.type = :commentType AND jc.id IS NOT NULL',
					'{{%notification}}.type != :commentType'
				],
				[':commentType' => JournalAddCommentNotification::$TYPE]
			);
    }
}