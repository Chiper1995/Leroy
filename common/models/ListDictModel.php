<?php
namespace common\models;

use common\components\ActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Class ListDictModel
 * @package common\models
 *
 * @property integer $id
 * @property string $name
 * @property integer $updated_at
 */
class ListDictModel extends ActiveRecord
{
    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            ['name', 'unique', 'message' => 'Запись с таким наименованием уже есть'],
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
            'updated_at' => 'Последнее обновление',
        );
    }

    public static function getList($callback = null)
    {
        $model = static::className();
        return static::getDb()->cache(
            function () use ($model, $callback) {
                /* @var ActiveRecord $model */
                /* @var ActiveQuery $query */
                $query = $model::find()->orderBy('name');
                if ($callback !== null) {
                    call_user_func($callback, $query);
                }
                return ArrayHelper::map($query->all(), 'id', 'name');
            },
            3600,
            static::getCacheDependency()
        );
    }

    public function getNamesByIdList($idList)
    {
        return static::getDb()->cache(
            function () use ($idList) {
                return
                    implode(', ', array_keys(
                        static::find()
                            ->andWhere(['id' => $idList])
                            ->select(['name'])
                            ->indexBy('name')
                            ->asArray()
                            ->all()
                    ));
            },
            3600,
            $this->getCacheDependency()
        );
    }

}