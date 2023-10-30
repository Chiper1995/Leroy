<?php
namespace common\models;

use common\components\ActiveRecord;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * Class ForumMessage
 * @package common\models
 *
 * @property integer $id
 * @property integer $theme_id
 * @property integer $is_first
 * @property integer $user_id
 * @property string $message
 * @property integer $created_at
 * @property integer $updated_at
 * @property ForumTheme $theme
 * @property User $user
 * @mixin ForumMessageQuery
 */
class ForumMessage extends ActiveRecord
{
    public function behaviors()
    {
        return [
            'TimestampBehavior' => [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }

    /**
    * @return array validation rules for model attributes.
    */
    public function rules()
    {
        return [
            ['message', 'required'],
            ['message', 'string', 'min'=>30],
            ['message', 'filter', 'filter' => 'trim'],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['message'];
        $scenarios['update'] = ['message'];
        return $scenarios;
    }

    public function attributeLabels()
    {
        return array(
            'message' => 'Сообщение',
            'created_at' => 'Создано',
            'updated_at' => 'Последнее обновление',
        );
    }

    /**
     * @return ActiveQuery
     */
    public function getTheme()
    {
        return $this->hasOne(ForumTheme::className(), ['id' => 'theme_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }


    public static function find()
    {
        return new ForumMessageQuery(get_called_class());
    }

}