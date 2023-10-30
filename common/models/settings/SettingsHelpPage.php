<?php
namespace common\models\settings;

use yii\base\Model;

class SettingsHelpPage extends Model
{
    public $content = '';

    public function rules()
    {
        return array(
            ['content', 'safe',],
        );
    }

    public function scenarios()
    {
        $scenarios['update'] = ['content',];
        return $scenarios;
    }

    public function attributeLabels()
    {
        return array(
            'content' => 'Контент',
        );
    }

}
