<?php
namespace common\models;

use common\components\ActiveRecord;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * Class City
 * @package common\models
 *
 * @property integer $id
 * @property string $name
 * @property integer $updated_at
 */
class City extends ListDictModel
{
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])
            ->viaTable('{{%user_city}}', ['city_id' => 'id']);
    }

    public function getUsersAndLocations()
    {
        return $this->getUsers()->with(['homeAdress','homeRepairLocation'])->all();
    }

    public function getUserLocations()
    {
        return $this->hasMany(UserLocation::className(), ['city_id' => 'id']);
    }

	/**
	 * @param null $callback
	 * @return mixed
	 * @throws \Exception
	 */
	public static function getList($callback = null)
	{
		$model = static::className();
		return static::getDb()->cache(
			function () use ($model, $callback) {
				/* @var ActiveRecord $model */
				/* @var ActiveQuery $query */
				$query = $model::find()->orderBy(new Expression('CASE name WHEN \'Москва\' THEN 1 WHEN \'Московская область\' THEN 2 WHEN \'Санкт-Петербург\' THEN 3 ELSE 4 END, name'));
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
