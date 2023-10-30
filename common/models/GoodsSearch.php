<?php
namespace common\models;

use paulzi\adjacencylist\AdjacencyListQueryTrait;
use yii\db\ActiveQuery;

/**
 * Class GoodsSearch
 * @package common\models
 *
 * @mixin AdjacencyListQueryTrait
 */
class GoodsSearch extends ActiveQuery
{
    use AdjacencyListQueryTrait;
}