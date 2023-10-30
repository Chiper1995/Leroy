<?php
namespace frontend\models\user;

use yii\base\Model;

/**
 * Class FamilySetCuratorForm
 * @package frontend\models\user
 */
class FamilySetCuratorForm extends Model
{
    public $family_id;
    public $curator_id;
    public $saved = false;

    public function attributeLabels()
    {
        return [
            'curator_id' => 'Куратор',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['curator_id', 'required'],
            ['curator_id', 'number', 'integerOnly' => true],

            ['family_id', 'required'],
            ['family_id', 'number', 'integerOnly' => true],
        ];
    }

}
