<?php
namespace frontend\models;


use common\components\PersistSearchStateTrait;
use Yii;
use yii\base\Model;
use yii\data\SqlDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

class FamilyPointsHistorySearch extends Model
{
	use PersistSearchStateTrait;

	public $familyId;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [];
	}

	public function attributeLabels()
	{
		return [
			'id' => '',
			'points' => 'Баллы',
			'date' => 'Дата',
			'user' => 'Кто списал/начислил',
			'comment' => 'Комментарий',
		];
	}

	public function search($params, $dataProviderConfig = [])
	{
		$sql = '
			SELECT T.points, T.`date`, u.fio AS `user`, T.`comment`, T.id, T.type
			FROM (
				SELECT points, updated_at AS `date`, null AS user_id, CONCAT(\'Начислено из записи дневника #\', j.id) AS `comment`, j.id, 1 AS `type`
				FROM {{%journal}} j 
				WHERE j.user_id = :family_id AND points <> 0
				UNION ALL 
				SELECT v.points, v.updated_at AS `date`, v.creator_id AS user_id, CONCAT(\'Начислено из визита #\', v.id) AS `comment`, v.id, 2 AS `type`
				FROM {{%visit}} v 
				WHERE v.user_id = :family_id AND points <> 0
				UNION ALL 
				SELECT -1 * s.points, s.created_at AS `date`, s.user_id, s.description AS `comment`, s.id, 3 AS `type`
				FROM {{%spending}} s 
				WHERE s.family_id = :family_id AND points <> 0
				UNION ALL 
				SELECT e.points, e.created_at AS `date`, e.user_id, e.description AS `comment`, e.id, 4 AS `type`
				FROM {{%earnings}} e
				WHERE e.family_id = :family_id AND points <> 0
				UNION ALL 
				SELECT gt.points, gt.created_at AS `date`, null AS user_id, CONCAT(\'Подарено за запись дневника #\', gt.journal_id) AS `comment`, gt.journal_id, 5 AS `type`
				FROM {{%gift}} gt
				WHERE gt.to_family_id = :family_id AND points <> 0
				UNION ALL 
				SELECT -1 * gf.points, gf.created_at AS `date`, null AS user_id, CONCAT(\'Подарил за запись дневника #\', gf.journal_id) AS `comment`, gf.journal_id, 6 AS `type`
				FROM {{%gift}} gf
				WHERE gf.from_family_id = :family_id AND points <> 0
			) AS T
				LEFT JOIN {{%user}} u ON u.id = T.user_id';
		$sqlParams = [':family_id' => $this->familyId];

		$count = Yii::$app->db->createCommand(
			'SELECT 
				COALESCE((SELECT COUNT(*) FROM {{%journal}} j WHERE j.user_id = :family_id AND points <> 0), 0) +
				COALESCE((SELECT COUNT(*) FROM {{%visit}} v WHERE v.user_id = :family_id AND points <> 0), 0) +
				COALESCE((SELECT COUNT(*) FROM {{%spending}} s WHERE s.family_id = :family_id AND points <> 0), 0) +
				COALESCE((SELECT COUNT(*) FROM {{%earnings}} e WHERE e.family_id = :family_id AND points <> 0), 0) +
				COALESCE((SELECT COUNT(*) FROM {{%gift}} gt WHERE gt.to_family_id = :family_id AND points <> 0), 0) -
				COALESCE((SELECT COUNT(*) FROM {{%gift}} gf WHERE gf.from_family_id = :family_id AND points <> 0), 0)
			FROM DUAL',
			[':family_id' => $this->familyId]
		)->queryScalar();

		$dataProvider = new SqlDataProvider(ArrayHelper::merge($dataProviderConfig, [
			'sql' => $sql,
			'totalCount' => intval($count),
			'params' => $sqlParams,
		]));

		// Восстанавливаем состояние
		$this->persistState($dataProvider);

		if ($this->load($params, StringHelper::basename(get_called_class())) && !$this->validate()) {
			return $dataProvider;
		}

		// Доп условия
		// ..

		return $dataProvider;
	}
}