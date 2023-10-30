<?php


namespace common\models;


use common\components\ActiveRecord;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "bs_user_view_journal".
 * Class UserViewJournal
 * @package common\models
 *
 * @property integer $id
 * @property integer $userId
 * @property integer $journalId
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property Journal $journal
 * @property User $user
 *
 */
class UserViewJournal extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_view_journal}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'journalId'], 'required'],
            [['userId', 'journalId'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['journalId'], 'exist', 'skipOnError' => true, 'targetClass' => Journal::className(), 'targetAttribute' => ['journalId' => 'id']],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userId' => 'id']],
            [['userId'], 'unique', 'targetAttribute' => ['userId', 'journalId']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userId' => 'Пользователь',
            'journalId' => 'Журнал',
            'createdAt' => 'Дата создания',
            'updatedAt' => 'Дата изменения',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'updatedAt',
                'value' => new Expression('now()')
            ]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJournal()
    {
        return $this->hasOne(Journal::className(), ['id' => 'journalId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
}