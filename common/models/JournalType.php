<?php
namespace common\models;
use yii\helpers\ArrayHelper;
/**
 * Class JournalType
 * @package common\models
 *
 * @property integer $id
 * @property string $name
 * @property integer points
 * @property integer $updated_at
 */
class JournalType extends ListDictModel
{
    const TASK_JOURNAL_TYPE = 6;
    const WORK_JOURNAL_TYPE = 1;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            ['name', 'unique', 'message' => 'Запись с таким наименованием уже есть'],

            ['points', 'required'],
            ['points', 'number', 'integerOnly' => true, 'min'=>0],
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
            'points' => 'Начисляется баллов',
            'updated_at' => 'Последнее обновление',
        );
    }

    /**
     * @param null $callback
     * @param bool $isInTask - зашёл ли пользователь в создание/редактирование записи из страницы Заданий
     * @return mixed
     * @throws \Exception
     */
    public static function getList($callback = null, $isInTask = false)
    {
        $model = static::className();
        return static::getDb()->cache(
            function () use ($model, $callback, $isInTask) {
                /* @var ActiveRecord $model */
                /* @var ActiveQuery $query */
                if($isInTask){
                    $query = $model::find()->orderBy('name');
                }else{
                    $query = $model::find()->where(['not', ['id' => 6]])->orderBy('name');
                }

                if ($callback !== null) {
                    call_user_func($callback, $query);
                }
                return ArrayHelper::map($query->all(), 'id', 'name');
            },
            3600,
            static::getCacheDependency()
        );
    }
}
