<?php

namespace tests\models;

use paulzi\adjacencylist\AdjacencyListBehavior;

/**
 * @property integer $id
 * @property string $path
 * @property integer $depth
 * @property integer $sort
 * @property string $slug
 *
 * @property Node[] $parents
 * @property Node[] $parentsOrdered
 * @property Node $parent
 * @property Node $root
 * @property Node[] $descendants
 * @property Node[] $descendantsOrdered
 * @property Node[] $children
 * @property Node[] $leaves
 * @property Node $prev
 * @property Node $next
 *
 * @method static Node|null findOne() findOne($condition)
 *
 * @mixin AdjacencyListBehavior
 */
class Node extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tree}}';
    }
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'tree' => [
                'class' => AdjacencyListBehavior::className(),
                'parentsJoinLevels'  => 0,
                'childrenJoinLevels' => 0,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @return NodeQuery
     */
    public static function find()
    {
        return new NodeQuery(get_called_class());
    }
}