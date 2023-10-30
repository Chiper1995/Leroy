<?php

namespace frontend\models\employee;

use yii\base\Model;
use Yii;

/**
 * UserOffice form
 */
class UserOffice extends Model
{
    public $login;
    public $password;
    public $password_confirm;
    public $email;
    public $name;
    public $surname;
    public $activity;
    public $activity_other;

    public function attributeLabels()
    {
        return [
            'login' => 'Логин',
            'password' => 'Пароль',
            'password_confirm' => 'Повтор пароля',
            'email' => 'Рабочий Email',
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'activity' => 'Вид деятельности',
            'activity_other' => 'Другой вид деятельности',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['login', 'filter', 'filter' => 'trim'],
            ['login', 'required'],
            ['login', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Пользователь с таким логином уже зарегистрирован.'],
            ['login', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'min' => 3, 'max' => 100],
            ['email', 'match', 'pattern' => '/^[a-zA-Z0-9_.+-]+@(?:(?:[a-zA-Z0-9-]+\.)?[a-zA-Z]+\.)?(leroymerlin.ru|LEROYMERLIN.RU)$/g', 'message' => 'Email не является рабочим.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 5],

            ['password_confirm', 'required'],
            ['password_confirm', 'string', 'min' => 5],
            ['password_confirm', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают.'],

            ['name', 'filter', 'filter' => 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 3, 'max' => 100],
            ['name', 'match', 'pattern' => '/^[А-ЯЁ]+(?:[_ -]?[а-яё])*$/g','message'=> 'Имя должно начинаться с заглавной буквы и содержать символы русского алфавита.'],

            ['surname', 'filter', 'filter' => 'trim'],
            ['surname', 'required'],
            ['surname', 'string', 'min' => 3, 'max' => 100],
            ['surname', 'match', 'pattern' => '/^[А-ЯЁ]+(?:[_ -]?[а-яё])*$/g','message'=> 'Фамилия должна начинаться с заглавной буквы и содержать символы русского алфавита.'],

            ['activity', 'required'],
            ['activity', 'in', 'range' => ['marketing', 'purchase', 'other']],

            ['activity_other', 'required',
                'when' => function ($model) {
                    return $model->activity == 'other';
                },
                'whenClient' => "function (attribute, value) {
                    return $('.field-useroffice-activity input:checked').val() == 'other';
                }"
            ],
		// Security check for what roles are acceptable for activity_other 
            ['activity_other', 'in', 'range' => ['marketing', 'viewJournalOnlyAllCities', 'marketing_plus']],
        ];
    }
}
