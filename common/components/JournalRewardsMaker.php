<?php
namespace common\components;

use common\models\Journal;
use common\models\JournalType;
use common\models\settings\Settings;
use common\models\settings\SettingsRewards;
use yii\helpers\ArrayHelper;

class JournalRewardsMaker
{
    /**@var Journal $journal*/
    private $journal;

    /**
     * RewardsMaker constructor.
     * @param Journal $journal
     */
    public function __construct($journal)
    {
        $this->journal = $journal;
    }

    public function make()
    {
        if ($this->journal->task != null) {
            $points = JournalType::findOne(JournalType::TASK_JOURNAL_TYPE)->points;
        }
        else {
            $points = max(ArrayHelper::merge([0], ArrayHelper::getColumn($this->journal->journalTypes, 'points')));
        }

        $this->journal->points = $points;
        $this->journal->save(false);
    }
}