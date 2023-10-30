<?php
namespace frontend\models\dialog;

use common\models\Dialog;
use common\models\DialogMessage;
use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Create new dialog and add message
 * Class AddMessageForm
 * @package frontend\models\dialog
 *
 * @property String $message
 */
class AddMessageForm extends Model
{
    public $message;

    public function attributeLabels()
    {
        return [
            'message' => 'Сообщение',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['message', 'filter', 'filter' => 'trim'],
            ['message', 'required'],
        ];
    }

    /**
     * @param Dialog $dialog
     * @param integer $authorId
     * @return DialogMessage|null
     * @throws \yii\db\Exception
     */
    public function saveMessage($dialog, $authorId)
    {
        $transaction = Yii::$app->db->beginTransaction();
        // Сообщение
        $message = new DialogMessage([
            'user_id' => $authorId,
            'message' => $this->message,
        ]);

        if ($message->save()) {
            $dialog->link('messages', $message);
        }
        else {
            $transaction->rollBack();
            return null;
        }

        // Обновляем дату изменения в диалоге
        $dialog->touch();

        $transaction->commit();
        return $message;
    }
}
