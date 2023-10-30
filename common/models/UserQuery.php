<?php
namespace common\models;

use yii\db\ActiveQuery;
use yii\db\Query;

class UserQuery extends ActiveQuery
{
    public function notDeleted($alias='')
    {
        if ($alias !== '')
            $alias = $alias.'.';

        return $this->andWhere(['<>', $alias.'status', User::STATUS_DELETED]);
    }

    public function active($alias='')
    {
        if ($alias !== '')
            $alias = $alias.'.';

        return $this->andWhere([$alias.'status' => [User::STATUS_ACTIVE, User::STATUS_END_REPAIR]]);
    }

    public function onlyUsers($alias='')
    {
        if ($alias !== '')
            $alias = $alias.'.';

        return $this->andWhere($alias.'role!=:role', [':role' => User::ROLE_FAMILY]);
    }

    /**
     * @param null|integer[]|integer $cityId
     * @param string $alias
     * @return $this
     */
    public function onlyFamilies($cityId = null, $alias='')
    {
        if ($alias !== '')
            $alias = $alias.'.';

        $this->andWhere([$alias.'role' => User::ROLE_FAMILY]);

        if ($cityId != null) {
            $usersInCitiesQuery = (new Query())->select('user_id')->from('{{%user_city}} uc')->where(['uc.city_id' => $cityId]);
            $this->andFilterWhere([$alias.'id' => $usersInCitiesQuery]);
        }

        return $this;
    }

    public function onlyAdministrators($alias='')
    {
        if ($alias !== '')
            $alias = $alias.'.';

        return $this->andWhere([$alias.'role' => User::ROLE_ADMINISTRATOR]);
    }

    /**
     * @param null|integer[]|integer $cityId
     * @param string $alias
     * @return $this
     */
    public function onlyFamiliesFioLogin($cityId = null, $alias='')
    {
        $_alias = ($alias !== '') ? $alias.'.' : '';

        return $this
            ->onlyFamilies($cityId, $alias)
            ->notDeleted()
            ->select(["*", "{$_alias}id, CONCAT({$_alias}fio, ' (', {$_alias}username, ')') AS fio"])
            ->orderBy($_alias.'fio');
    }

    public function usersForDialog($conditions = [], $alias='')
    {
        if ($alias !== '')
            $alias = $alias.'.';

        $res = $this
            ->select(["*", "{$alias}id, CONCAT({$alias}fio, ' (', {$alias}username, ')') AS fio"])
            ->orderBy('fio');

        foreach($conditions as $condition) {
            if (count($condition)==1)
                $this->orWhere($condition[0]);
            else
                $this->orWhere($condition[0], $condition[1]);
        }

        $this->notDeleted();

        return $res;
    }

    /**
     * @param null|integer[]|integer $cityId
     * @param string $alias
     * @return $this
     */
    public function usersCurators($cityId = null, $alias='')
    {
        $_alias = ($alias !== '') ? $alias.'.' : '';

        $this
            ->active()
            ->andWhere([$alias.'role' => User::ROLE_SHOP]);

        if ($cityId != null) {
            $usersInCitiesQuery = (new Query())->select('user_id')->from('{{%user_city}} uc')->where(['uc.city_id' => $cityId]);
            $this->andFilterWhere([$alias.'id' => $usersInCitiesQuery]);
        }

        return $this;
    }

    public function withPoints($alias='{{%user}}')
    {
        if ($alias !== '')
            $alias = $alias.'.';

        return $this
            ->select([
                $alias.'*',
                '('.
                    'COALESCE((SELECT SUM(points) FROM {{%journal}} j WHERE j.user_id = '.$alias.'id), 0) + '.
                    'COALESCE((SELECT SUM(points) FROM {{%visit}} v WHERE v.user_id = '.$alias.'id), 0) - '.
                    'COALESCE((SELECT SUM(points) FROM {{%spending}} s WHERE s.family_id = '.$alias.'id), 0) +'.
                    'COALESCE((SELECT SUM(points) FROM {{%earnings}} e WHERE e.family_id = '.$alias.'id), 0) +'.
                    'COALESCE((SELECT SUM(points) FROM {{%gift}} gt WHERE gt.to_family_id = '.$alias.'id), 0) -'.
                    'COALESCE((SELECT SUM(points) FROM {{%gift}} gf WHERE gf.from_family_id = '.$alias.'id), 0)'.
                ') AS points'
            ]);
    }
}