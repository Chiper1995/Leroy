<?php
namespace frontend\models\user;

use yii\base\Model;
use Yii;

/**
 * Class FamilyReducePointsForm
 * @package frontend\models\user
 */
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
