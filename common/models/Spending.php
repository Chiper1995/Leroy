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
 * Class Spending
 * @package common\models
 *
 * @property integer $id
 * @property integer $family_id
 * @property integer $user_id
 * @property integer $points
 * @property string $description
 * @property integer $created_at
 * @property integer $event
 */
class Spending extends BasePoints
{
    public $event = AppEvents::EVENT_USER_SPEND_POINTS;

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = [];
        $scenarios['update'] = [];
        return $scenarios;
    }
}