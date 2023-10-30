<?php
namespace common\models;

use yii\db\ActiveQuery;

class ShopQuery extends ActiveQuery
{
    public function withCity($city_id)
    {
        return $this->andWhere(['city_id' => $city_id]);
    }

    public function withTerm($term)
    {
        return $this->andWhere(['like', 'number', $term]);
    }
}