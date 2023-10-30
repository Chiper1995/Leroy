<?php
namespace frontend\models\registration;

use yii\base\Model;
use Yii;

/**
 * Profile form
 */
class Profile extends Model
{
    public $username;
    public $email;
    public $password;
    public $password_confirm;
    public $fio;
    public $phone;
    public $address;
    public $city_id;
    public $family_name;
    public $agreed;

    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
            'password_confirm' => 'Повтор пароля',
            'email' => 'Email',
            'fio' => 'ФИО',
            'phone' => 'Телефон',
            'address' => 'Адрес ремонта',
            'city_id' => 'Город, где идет ремонт или строительство',
            'family_name' => 'Как называть вашу семью?',
            'agreed' => 'Я даю согласие на обработку персональных данных.',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Пользователь с таким логином уже зарегистрирован.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'min' => 3, 'max' => 100],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Пользователь с такой почтой уже зарегистрирован.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 5],

            ['password_confirm', 'required'],
            ['password_confirm', 'string', 'min' => 5],
            ['password_confirm', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают'],

            ['family_name', 'filter', 'filter' => 'trim'],
            ['family_name', 'required'],
            ['family_name', 'string', 'min' => 5, 'max' => 255],

            ['fio', 'filter', 'filter' => 'trim'],
            ['fio', 'required'],
            ['fio', 'string', 'min' => 3, 'max' => 100],

            ['phone', 'filter', 'filter' => 'trim'],
            ['phone', 'required'],
            ['phone', 'string', 'min' => 5, 'max' => 20],

            ['address', 'filter', 'filter' => 'trim'],
            ['address', 'required'],

            ['city_id', 'required'],
            ['city_id', 'number', 'integerOnly' => true],

			['agreed', 'required'],
			['agreed', 'in', 'range' => [true], 'message' => 'Необходимо дать согласие на обработку персональных данных'],
        ];
    }
}
