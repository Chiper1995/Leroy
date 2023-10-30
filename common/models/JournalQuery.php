<?php
namespace common\models;

use yii\db\ActiveQuery;

class JournalQuery extends ActiveQuery
{
    public function allJournal($alias='')
    {
        if ($alias !== '')
            $alias = $alias.'.';

        return $this;
    }

    public function myJournal()
    {
        return $this
            ->andWhere(['user_id' => \Yii::$app->user->id]);
    }

    public function published()
    {
        return $this
            ->andWhere(['status' => Journal::STATUS_PUBLISHED]);
    }

    public function onCheck($alias='')
    {
        if ($alias !== '')
            $alias = $alias.'.';

        return $this
            ->andWhere([$alias.'status' => Journal::STATUS_ON_CHECK]);
    }

    public function familyJournal($familyId)
    {
        return $this
            ->andWhere(['user_id' => $familyId]);
    }

    public function userAllJournal($alias='')
    {
        if ($alias !== '')
            $alias = $alias.'.';

        return $this
            ->andWhere([$alias.'status' => Journal::STATUS_PUBLISHED])
            ->andWhere([$alias.'visibility' => Journal::VISIBILITY_FOR_ALL]);
    }

    /**
     * Возвращает объект запроса на поиск id постов по id магазина в привязанных товарах.
     *
     * @param $shopId
     * @return $this
     */
    public function byGoodsShop($shopId)
    {
        $shopQuery = (new \yii\db\Query())
            ->select('journal_id')
            ->from('{{%journal_goods}} jg')
            ->where(['jg.goods_shop_id' => $shopId]);

        return $this->andWhere(['id' => $shopQuery]);
    }

    /**
     * Возвращает объект запроса на посты с привязанными товарами.
     *
     * @return $this
     */
    public function withGoods()
    {
        return $this
            ->leftJoin(
                JournalGoods::tableName(),
                JournalGoods::tableName() . '.journal_id = ' . Journal::tableName() . '.id'
            );
    }

    /**
     * Возвращает объект запроса на посты с привязанными товарами и магазинами.
     *
     * @return $this
     */
    public function withShops()
    {
        return $this
            ->withGoods()
            ->leftJoin(
                GoodsShop::tableName(),
                GoodsShop::tableName() . '.id = ' . JournalGoods::tableName() . '.goods_shop_id'
            );
    }

    /**
     * Возвращает объект запроса на посты с выбором только названий магазинов там, где установлены.
     *
     * @return $this
     */
    public function getShopName()
    {
        return $this
            ->addSelect(GoodsShop::tableName() . '.name')
            ->andWhere(['not', [GoodsShop::tableName() . '.name' => null]]);
    }

    public function getUserId()
    {
        return $this->select('user_id');
    }
}