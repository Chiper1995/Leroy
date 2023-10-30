<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 10.06.2018
 * Time: 13:32
 */

namespace frontend\models\user;

use common\models\User;
use yii\base\Model;
use Yii;

class FamilyGiveGiftForm extends Model
{
    public $journal_id;
    public $points;
    public $saved = false;

    public function attributeLabels()
    {
        return [
            'points' => \Yii::t('app', 'Сколько подарить баллов'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['journal_id', 'required'],
            ['journal_id', 'number', 'integerOnly' => true],

            ['points', 'required'],
            ['points', 'number', 'integerOnly' => true, 'min'=>1],
        ];
    }
}