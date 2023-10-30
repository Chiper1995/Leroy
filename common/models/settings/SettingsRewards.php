<?php
namespace common\models\settings;

use common\models\JournalType;
use yii\base\Model;

class SettingsRewards extends Model
{
    public $postWithoutPhotoAndWithoutGoods = 0;
    public $postWithoutPhotoAndWithGoods = 0;
    public $postWithPhotoAndWithoutGoods = 0;
    public $postWithPhotoAndWithGoods = 0;
    public $task = 0;
    public $visitChangeTime = 0;
    public $visitClientTime = 0;

    public function rules()
    {
        return array(
            ['postWithoutPhotoAndWithoutGoods', 'number', 'integerOnly' => true,],
            ['postWithoutPhotoAndWithGoods', 'number', 'integerOnly' => true,],
            ['postWithPhotoAndWithoutGoods', 'number', 'integerOnly' => true,],
            ['postWithPhotoAndWithGoods', 'number', 'integerOnly' => true,],
            ['task', 'number', 'integerOnly' => true,],
            ['visitChangeTime', 'number', 'integerOnly' => true,],
            ['visitClientTime', 'number', 'integerOnly' => true,],
        );
    }

    public function scenarios()
    {
        $scenarios['update'] = [
            'postWithoutPhotoAndWithoutGoods', 'postWithoutPhotoAndWithGoods', 'postWithPhotoAndWithoutGoods', 'postWithPhotoAndWithGoods',
            'task',
            'visitChangeTime', 'visitClientTime',
        ];
        return $scenarios;
    }

    public function attributeLabels()
    {
        return array(
            'postWithoutPhotoAndWithoutGoods' => 'Пост без фото и покупок',
            'postWithoutPhotoAndWithGoods' => 'Пост без фото, но с покупками',
            'postWithPhotoAndWithoutGoods' => 'Пост с фото, но без покупок',
            'postWithPhotoAndWithGoods' => 'Пост с фото и с покупками',
            'task' => 'Выполненное задание',
            'visitChangeTime' => 'Подтверждение визита, но с переносом времени',
            'visitClientTime' => 'Подтверждение визита на время, назначенное клиентy',
        );
    }

    private $journalTypes = null;

    /**
     * @return JournalType[]
     */
    public function getJournalTypes()
    {
        if ($this->journalTypes === null) {
            $this->journalTypes = JournalType::find()->orderBy('name')->all();
        }
        return $this->journalTypes;
    }

    public function saveJournalTypesRewards($journalTypesRewardsData)
    {
        if (is_array($journalTypesRewardsData)) {
            foreach ($journalTypesRewardsData as $journalTypeId => $journalTypePoints) {
                if (($journalType = JournalType::findOne($journalTypeId)) !== null) {
                    $journalType->points = intval($journalTypePoints);
                    $journalType->save();
                }
            }
        }
    }
}
