<?php
namespace common\models;

/**
 * Class JournalType
 * @package common\models
 *
 * @property integer $id
 * @property string $name
 * @property integer $updated_at
 */
class JournalTag extends ListDictModel
{

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            ['name', 'unique', 'message' => 'Запись с таким наименованием уже есть'],
        ];
    }

    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['name'];
        $scenarios['update'] = ['name'];
        return $scenarios;
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Наименование',
            'updated_at' => 'Последнее обновление',
        );
    }
}
