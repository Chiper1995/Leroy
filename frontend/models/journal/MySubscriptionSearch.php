<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 16.07.2018
 * Time: 1:36
 */

namespace frontend\models\journal;

use common\models\Journal;
use common\models\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use Yii;

class MySubscriptionSearch extends Journal
{
    public $status;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Journal::tableName();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'number', 'integerOnly' => true],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param Journal $modelClass
     * @param array $params
     * @param array $dataProviderConfig
     *
     * @return ActiveDataProvider
     */
    public function search($modelClass, $params, $dataProviderConfig = [])
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;

        $query = $modelClass::find()
            ->from('{{%journal}} journal')
            ->userAllJournal('journal')
            ->joinWith('user as u', true)
            ->with('photos', 'likeUsers')
            ->andWhere(['journal.user_id' => $user->getSubscriptionToUserIds()]);

        $dataProvider = new ActiveDataProvider(ArrayHelper::merge($dataProviderConfig, [
            'query' => $query,
        ]));

        if ($this->load($params, StringHelper::basename(get_called_class())) && !$this->validate()) {
            return $dataProvider;
        }
        if ($this->status == Journal::FAVORITE_POST) {
            $query = $modelClass::find()
                ->from('{{%journal}} journal')
                ->userAllJournal('journal')
                ->joinWith('user as u', true)
                ->with('photos', 'likeUsers')
                ->andWhere(['journal.id' => $user->getFavoriteJournals()]);

            $dataProvider = new ActiveDataProvider(ArrayHelper::merge($dataProviderConfig, [
                'query' => $query,
            ]));

            if ($this->load($params, StringHelper::basename(get_called_class())) && !$this->validate()) {
                return $dataProvider;
            }
        }

        return $dataProvider;
    }
}