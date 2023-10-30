<?php
namespace frontend\models\dialog;

use common\models\Dialog;
use common\models\DialogMessage;
use common\models\User;
use common\rbac\Rights;
use yii\base\Model;
use Yii;

/**
 * Create new dialog and add message
 * Class NewDialogForm
 * @package frontend\models\dialog
 *
 * @property integer[] $users_id
 * @property String $subject
 * @property String $message
 */
class NewDialogForm extends Model
{
    public $users_id;
    public $subject;
    public $message;

    public function attributeLabels()
    {
        return [
            'users_id' => 'Получатели',
            'subject' => 'Тема',
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

            ['subject', 'filter', 'filter' => 'trim'],
            ['subject', 'required'],
            ['subject', 'string', 'max' => 255],

            [['users_id'], 'each', 'rule' => ['number', 'integerOnly' => true]],
            ['users_id', 'required'],
        ];
    }

    /**
     * @param $authorId
     * @return DialogMessage|null
     * @throws \yii\db\Exception
     */
    public function createDialog($authorId)
    {
        $transaction = Yii::$app->db->beginTransaction();
        if (($dialog = $this->newDialog($authorId)) === null) {
            $transaction->rollBack();
            return null;
        }

        // Добавляем автора
        /**@var User $author*/
        $author = User::findOne($authorId);
        $dialog->link('users', $author);

        // Если сообщение куратору, то добавляем администраторов
        if (in_array($author->curator_id, $this->users_id))
           $this->addUsersInDialogBetweenUserAndCurator($dialog);

        // Добавляем остальных пользователей
        $this->addUsersInDialog($dialog);

        // Сообщение
        if (($message = $this->addMessageInDialog($dialog, $authorId)) === null) {
            $transaction->rollBack();
            return null;
        }

        $transaction->commit();
        return $message;
    }

    /**
     * @param integer $authorId
     * @return Dialog|null
     */
    protected function newDialog($authorId) {
        $dialog = new Dialog([
            'author_id' => $authorId,
            'subject' => $this->subject,
        ]);

        if ($dialog->save())
            return $dialog;
        else
            return null;
    }

    /**
     * @param Dialog $dialog
     * @param integer $authorId
     * @return DialogMessage|null
     */
    protected function addMessageInDialog($dialog, $authorId) {
        $message = new DialogMessage([
            'user_id' => $authorId,
            'message' => $this->message,
        ]);

        if ($message->save()) {
            $dialog->link('messages', $message);
            return $message;
        } else {
            return null;
        }
    }

    /**
     * @param Dialog $dialog
     */
    protected function addUsersInDialog($dialog) {
        $users = User::findAll($this->users_id);
        foreach ($users as $user) {
            $dialog->link('users', $user);
        }
    }

    /**
     * @param Dialog $dialog
     */
    protected function addUsersInDialogBetweenUserAndCurator($dialog) {
        /**@var $users User[] */
        $users = User::find()->notDeleted()->all();

        foreach ($users as $user) {
            if (\Yii::$app->getAuthManager()->checkAccess($user->id, Rights::READ_DIALOGS_BETWEEN_USER_AND_CURATOR)) {
                $dialog->link('users', $user, ['read_only' => 1]);
            }
        }
    }
}
