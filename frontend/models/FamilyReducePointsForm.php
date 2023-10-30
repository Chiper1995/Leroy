<?php
namespace frontend\models;

use common\models\User;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;

class FamilyReducePointsForm extends Model
{
    public $family_id;
    public $points;
    public $description;
    public $saved = false;

    public function attributeLabels()
    {
        return [
            'points' => 'Сколько списать?',
            'description' => 'Комментарий',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['points', 'required'],
            ['points', 'number', 'integerOnly' => true, 'min'=>1],

            ['family_id', 'required'],
            ['family_id', 'number', 'integerOnly' => true],

            ['description', 'safe'],
        ];
    }

}
