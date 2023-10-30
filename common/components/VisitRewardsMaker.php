<?php
namespace common\components;

use common\models\Visit;
use common\models\settings\Settings;
use common\models\settings\SettingsRewards;

class VisitRewardsMaker
{
    /**@var Visit $visit*/
    private $visit;

    /**
     * RewardsMaker constructor.
     * @param Visit $visit
     */
    public function __construct($visit)
    {
        $this->visit = $visit;
    }

    private function make($timeChanged)
    {
        /**@var SettingsRewards $settings*/
        $settings = Settings::SettingsRewards();

        if ($timeChanged)
            $points = $settings->visitChangeTime;
        else
            $points = $settings->visitClientTime;

        $this->visit->points = $points;
        $this->visit->save(false);
    }

    public function makeWithoutTimeChanging()
    {
        $this->make(false);
    }

    public function makeWithTimeChanging()
    {
        $this->make(true);
    }
}