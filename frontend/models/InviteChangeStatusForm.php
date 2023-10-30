<?php
namespace frontend\models;

use yii\base\Model;

/**
 * Class InviteChangeStatusForm
 * @package frontend\models
 */
class InviteChangeStatusForm extends Model
{
    public $invite_id;
    public $status;
    public $saved = false;

    public function attributeLabels()
    {
        return [
            'status' => 'Статус',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'required'],
            ['status', 'number', 'integerOnly' => true],

            ['invite_id', 'required'],
            ['invite_id', 'number', 'integerOnly' => true],
        ];
    }

}
