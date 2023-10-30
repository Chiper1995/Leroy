<?php
/**
 * @var $this yii\web\View
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var $searchModel frontend\models\journal\AllJournalSmartSearch
 */

// Генерируем PDF
\common\components\SmartSearchResultsToDOCXExporter::export($searchModel->smartSearch, $dataProvider, Yii::$app->request->get('withImages') == '1');

// Отрубаем дальнейшую загрузку всего
die();