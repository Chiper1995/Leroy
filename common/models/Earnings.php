<?php
namespace common\models;

use common\components\ActiveRecord;
use common\events\AppEvents;
use common\models\notifications\Notification;
use Yii;
use yii\base\Event;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Class Earnings
 * @package common\models
 *
 * @property integer $id
 * @property integer $family_id
 * @property integer $user_id
 * @property integer $points
 * @property string $description
 * @property integer $created_at
 */
class Earnings extends BasePoints
{
    public $event = AppEvents::EVENT_USER_EARNING_POINTS;

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = [];
        $scenarios['update'] = [];
        return $scenarios;
    }
}