<?php
namespace common\models\interfaces;


use yii\data\ActiveDataProvider;

interface ISearchModel
{
    /**
     * Creates data provider instance with search query applied
     *
     * @param class-name $modelClass
     * @param array $params
     * @param array $dataProviderConfig
     *
     * @return ActiveDataProvider
     */
    public function search($modelClass, $params, $dataProviderConfig = []);
}