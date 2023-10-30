<?php
namespace common\models;

use common\components\ActiveRecord;
use Yii;

/**
 * Class Presentation
 * @package common\models
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $updated_at
 * @property string $file
 * @property integer help_id
 * @property Help $help
 */
class Presentation extends ActiveRecord
{
    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['title', 'filter', 'filter' => 'trim'],
            ['title', 'required'],
            ['title', 'string', 'min' => 3, 'max' => 250],

            ['content', 'filter', 'filter' => 'trim'],

            ['file', 'string'],
            ['file', 'required'],

            ['help_id', 'required'],
            ['help_id', 'number', 'integerOnly' => true],
        ];
    }


    public function getHelp()
    {
        return $this->hasOne(Help::className(), ['id' => 'help_id']);
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['title', 'content', 'file', 'help_id'];
        $scenarios['update'] = ['title', 'content', 'file', 'help_id'];
        $scenarios['view'] = [];
        return $scenarios;
    }

    public function transactions()
    {
        return [
            'create' => self::OP_INSERT,
            'update' => self::OP_ALL,
        ];
    }

    public function attributeLabels()
    {
        return array(
            'title' => 'Заголовок страницы',
            'content' => 'Текст',
            'updated_at' => 'Последнее обновление',
            'file' => 'Файл',
            'help_id' => 'Страница справки'
        );
    }
}