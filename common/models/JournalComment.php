<?php
namespace common\models;

use common\components\ActiveRecord;
use common\events\AppEvents;
use common\models\notifications\Notification;
use paulzi\adjacencylist\AdjacencyListBehavior;
use Yii;
use yii\base\Event;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;

/**
 * Class JournalComment
 * @package common\models
 *
 * @property integer $id
 * @property integer $parent_id
 * @property integer $journal_id
 * @property integer $user_id
 * @property string $content
 * @property integer $updated_at
 *
 * @property Journal $journal
 * @property User $user
 * @property Notification[] $linkedNotifications
 *
 * @mixin AdjacencyListBehavior
 *
 */
class JournalComment extends ActiveRecord
{
    public function behaviors()
    {
        return [
            'AdjacencyListBehavior' => [
                'class' => AdjacencyListBehavior::className(),
                'sortAttribute' => 'updated_at',
            ],
            'TimestampBehavior' => [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getJournal()
    {
        return $this->hasOne(Journal::className(), ['id' => 'journal_id'])->inverseOf('comments');
    }

    /**
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getLinkedNotifications()
    {
        return $this->hasMany(Notification::className(), ['journal_comment_id' => 'id']);
    }

    public function rules()
    {
        return [
            ['content', 'filter', 'filter' => 'trim'],
            ['content', 'required'],
            ['content', 'string', 'min' => 3, 'tooShort'=>'Текст комментария слишком короткий'],

            ['parent_id', 'number', 'integerOnly' => true],
        ];
    }

    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['content', 'parent_id'];
        return $scenarios;
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'journal_id' => 'Дневник',
            'content' => 'Комментарий',
            'updated_at' => 'Последнее обновление'
        );
    }

    public function attributes()
    {
        return [
            'created_at',
            'id',
            'parent_id',
            'journal_id',
            'user_id',
            'content',
            'updated_at',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // Оповещение о новом комментарии
        Yii::$app->trigger(AppEvents::EVENT_JOURNAL_ADD_COMMENT, new Event(['sender' => $this]));
    }

    public function afterDelete()
    {
        parent::afterDelete();

        // Оповещение об удалении комментария
        Yii::$app->trigger(AppEvents::EVENT_JOURNAL_DELETE_COMMENT, new Event(['sender' => $this->journal]));
    }

    public function beforeDelete()
    {
        if (!$this->isNewRecord) {
            foreach ($this->linkedNotifications as $notification) {
                $notification->delete();
            }

			if ($this->getChildren()->count() > 0) {
				$ids = $this->getDescendantsIds(null, true);
				/** @var JournalComment[] $comments */
				$comments = JournalComment::findAll($ids);
				foreach ($comments as $comment) {
					foreach ($comment->linkedNotifications as $notification) {
						$notification->delete();
					}
				}
			}
        }

        return parent::beforeDelete();
    }
}
