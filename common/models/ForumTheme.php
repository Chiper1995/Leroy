<?php
namespace common\models;

use common\components\ActiveRecord;
use Yii;
use yii\db\ActiveQuery;

/**
 * Class ForumTheme
 * @package common\models
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 * @property string $description
 * @property integer $updated_at
 * @property integer $is_messages_theme
 * @property ForumTheme $parentTheme
 * @property ForumMessage $lastMessage
 * @mixin ForumThemeQuery
 */
class ForumTheme extends ActiveRecord
{
    /**
    * @return array validation rules for model attributes.
    */
    public function rules()
    {
        return [
            ['name', 'required'],
            ['name', 'filter', 'filter' => 'trim'],
            ['name', 'string', 'max'=>250],

            ['description', 'filter', 'filter' => 'trim'],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['name', 'description'];
        $scenarios['update'] = ['name', 'description'];
        return $scenarios;
    }

    public function attributeLabels()
    {
        return array(
            'name' => 'Название',
            'description' => 'Описание',
            'parent_id' => 'Родительская тема',
            'updated_at' => 'Последнее обновление',
        );
    }

    /**
     * @return ActiveQuery
     */
    public function getParentTheme()
    {
        return $this->hasOne(ForumTheme::className(), ['id' => 'parent_id']);
    }

    /**
     * @return int
     */
    public function themesCount()
    {
        return intval($this->find()->forumThemes($this->id)->count());
    }

    /**
     * @return int
     */
    public function messagesCount()
    {
        return intval(ForumMessage::find()->forumThemeMessages($this->id)->count());
    }

    /**
     * Последнее сообщение в теме и подчиненных темах
     * @return null|ForumMessage
     */
    public function getLastMessage()
    {
        $id =
            $this->getDb()->createCommand(
                'SELECT id FROM {{%forum_message}} '.
                'WHERE '.
                '  theme_id = :themeId '.
                '  OR theme_id IN (SELECT id FROM {{%forum_theme}} WHERE parent_id = :themeId) '.
                '  OR theme_id IN (SELECT id FROM {{%forum_theme}} WHERE parent_id IN (SELECT id FROM {{%forum_theme}} WHERE parent_id = :themeId)) '.
                'ORDER BY updated_at DESC '.
                'LIMIT 1',
                [
                    ':themeId' => $this->id
                ]
            )->queryScalar();

        if ($id != null)
            return ForumMessage::findOne($id);
        else
            return null;
    }

    /**
     * Первое сообщение именно в этой теме, только для тем-обсуждений
     * @return null|ForumMessage
     */
    public function getFirstMessage()
    {
        return ForumMessage::find()->forumThemeFirstMessage($this->id)->one();
    }

    /**
     * @return ForumThemeQuery
     */
    public static function find()
    {
        return new ForumThemeQuery(get_called_class());
    }

    public function beforeDelete()
    {
        if (!$this->isNewRecord) {
            // Удаляем сообщения
            $childrenMessages = ForumMessage::find()->forumThemeMessages($this->id)->all();
            foreach ($childrenMessages as $message) {
                $message->delete();
            }

            // Удаляем подчиненные темы
            $childrenThemes = self::find()->forumThemes($this->id)->all();
            foreach ($childrenThemes as $theme) {
                $theme->delete();
            }
        }
        return parent::beforeDelete();
    }


}