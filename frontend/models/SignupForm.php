<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $fio;
    public $phone;
    public $address;
    public $city_id;
    public $object_repair_id;

    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
            'email' => 'Email',
            'fio' => 'ФИО',
            'phone' => 'Телефон',
            'address' => 'Адрес',
            'city_id' => 'Город',
            'object_repair_id' => 'Объект ремонта',
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

            ['object_repair_id', 'required'],
            ['object_repair_id', 'number', 'integerOnly' => true],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->fio = $this->fio;
            $user->phone = $this->phone;
            $user->address = $this->address;
            $user->city_id = $this->city_id;
            $user->object_repair_id = $this->object_repair_id;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }
}
