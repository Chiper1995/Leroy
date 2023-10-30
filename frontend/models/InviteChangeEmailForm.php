<?php
namespace frontend\models;

use yii\base\Model;
use Yii;

/**
 * Class InviteChangeEmailForm
 * @package frontend\models
 */
class InviteChangeEmailForm extends Model
{
    public $invite_id;
    public $email;
    public $saved = false;

    public function attributeLabels()
    {
        return [
            'email' => 'Email',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			['email', 'filter', 'filter' => 'trim'],
			['email', 'required'],
			['email', 'email'],
			['email', 'string', 'min' => 3, 'max' => 100],

            ['invite_id', 'required'],
            ['invite_id', 'number', 'integerOnly' => true],
        ];
    }

}
