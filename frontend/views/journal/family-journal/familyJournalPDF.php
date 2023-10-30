<?php
/**
 * @var $this yii\web\View
 * @var \frontend\models\journal\FamilyJournalSearch $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var \common\models\User $family
 */

// Генерируем PDF
\common\components\FamilyJournalToPDFExporter::export($family, $dataProvider);

// Отрубаем дальнейшую загрузку всего
die();