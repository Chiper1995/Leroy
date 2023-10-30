<?php
/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

/* @var $help string */
/* @var $user \common\models\User */

?>

<h1>Ваша учётная запись активирована</h1>
<div style="border-bottom: dashed 1px silver;padding: 6px;">
    <table>
        <tr>
            <th align="right">Страница входа:</th>
            <td><a href="<?= Yii::$app->params['startUrl']?>"><?= Yii::$app->params['startUrl']?></a></td>
        </tr>
        <tr>
            <th align="right">Ваш логин:</th>
            <td><?= $user->username?></td>
        </tr>
    </table>
</div>
<div>
    <?= $help ?>
</div>
