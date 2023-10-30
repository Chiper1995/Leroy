<?php
namespace common\models;

use common\components\ActiveRecord;
use common\events\AppEvents;
use yii\base\Event;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Class Invite
 * @package common\models
 * @property integer $id
 * @property string $session_id
 * @property integer $status
 * @property integer $sex
 * @property string $age
 * @property integer $family
 * @property integer $children
 * @property integer $repair_status
 * @property integer $repair_when_finish
 * @property string $repair_object_other
 * @property integer $have_cottage
 * @property integer $plan_cottage_works
 * @property integer $who_worker
 * @property integer $who_chooser
 * @property integer $who_buyer
 * @property string $shop_name
 * @property string $fio
 * @property string $phone
 * @property string $email
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $money
 * @property integer $distance
 * @property integer $city_id
 * @property string $city_other
 *
 * @property City $city
 *
 * @property string[] $repairObject
 * @property string[] $typeOfRepair
 */
class Invite extends ActiveRecord
{
	const STATUS_NEW = 0;
	const STATUS_EMAIL_SENT = 1;
	const STATUS_REGISTERED = 2;
	const STATUS_REJECTED = 3;
	//const STATUS_ARCHIVE = 4;

	public static $STATUS_LIST = [
		self::STATUS_NEW => 'Новая',
		self::STATUS_EMAIL_SENT => 'Письмо отправлено',
		self::STATUS_REGISTERED => 'Зарегистрировались',
		self::STATUS_REJECTED => 'Отклонены',
		//self::STATUS_ARCHIVE => 'Архив',
	];

    // Укажите Ваш пол
    public static $L_SEX = [
        1 => 'Мужской',
        2 => 'Женский',
	];

    // бы Вы могли описать Ваше семейное положение?
    public static $L_FAMILY = [
        1 => 'Не замужем/Холост',
        2 => 'Живем вместе, но официально не женаты',
        3 => 'Замужем/женат',
        4 => 'В разводе',
        5 => 'Вдова/вдовец',
	];

    // Есть ли у Вас дети, проживающие вместе с Вами?
    public static $L_HAVE_CHILDREN = [
        1 => 'Детей нет',
        2 => '1 ребенок',
        3 => '2-е детей',
        4 => '3 ребенка и больше',
	];

    // Какое утверждение лучше всего описывает Вашу ситуацию?
    public static $L_REPAIR_STATUS = [
        1 => 'Ремонт пока не начался - только планируем его начать в ближайшее время',
        2 => 'Ремонт только начался – часть работ уже выполнена, но ещё многое предстоит сделать',
        3 => 'Ремонт в самом разгаре - половина работ уже выполнена, но половина только предстоит',
        4 => 'Ремонт почти закончен - большая часть работ выполнена, осталось совсем немного',
        5 => 'Ремонт недавно был закончен (менее 3 месяцев назад)',
        6 => 'Ремонт был закончен давно (более 3 месяцев назад)',
	];

    // Когда Вы планируете завершить ремонт / строительство?
    public static $L_REPAIR_WHEN_FINISH = [
        1 => 'В течение ближайшего месяца',
        2 => 'Через 2-3 месяца',
        3 => 'Не ранее, чем через полгода',
        4 => 'Пока сложно сказать – ремонт / строительство может растянуться надолго',
	];

    // Скажите, есть ли у Вас дача?
    public static $L_HAVE_COTTAGE = [
        1 => 'Да',
        2 => 'Нет, но планирую приобрести в этом году',
        3 => 'Нет',
	];

    // Планируете ли Вы в этом году проводить какие-то работы на даче: посадка растений, строительство или обустройство (в т.ч. баня, беседка, зона отдыха, качели, бассейн и т.п.)
    public static $L_PLAN_COTTAGE_WORKS = [
        1 => 'Да',
        2 => 'Нет',
        3 => 'Пока не решил(а)',
	];

    // Кто выполняет или будет выполнять работы по строительству / ремонту?
    public static $L_WHO_WORKER = [
        1 => 'Я сам(а) и члены семьи',
        2 => 'В основном я сам(а) и члены семьи, незначительную часть – профессиональные мастера',
        3 => 'В равной степени я сам(а) и члены семьи и профессиональные мастера',
        4 => 'Незначительную часть работ я сам(а) и члены семьи, основную часть – профессиональные мастера',
        5 => 'Все работы выполняют профессиональные мастера',
	];

    // Кто принимает или будет принимать решения по выбору товаров для строительства и ремонта?
    public static $L_WHO_CHOOSER = [
        1 => 'Я сам(а) и члены семьи',
        2 => 'В основном я сам(а) и члены семьи, незначительную часть – профессиональные мастера',
        3 => 'В равной степени я сам(а) и члены семьи и профессиональные мастера',
        4 => 'Незначительную часть - я сам(а) и члены семьи, основную часть – профессиональные мастера',
        5 => 'Все товары выбирают / будут выбирать профессиональные мастера / дизайнер',
	];

    // Кто покупает или будет покупать товары для строительства и ремонта?
    public static $L_WHO_BUYER = [
        1 => 'Я сам(а) и члены семьи',
        2 => 'В основном я сам(а) и члены семьи, незначительную часть – профессиональные мастера',
        3 => 'В равной степени я сам(а) и члены семьи и профессиональные мастера',
        4 => 'Незначительную часть - я сам(а) и члены семьи, основную часть – профессиональные мастера',
        5 => 'Все товары покупают / будут покупать профессиональные мастера',
	];

    // Где Вы покупаете / планируете покупать товары для ремонта / строительства?
    public static $L_WHERE_BUY = [
        1 => 'В гипермаркетах для ремонта и обустройства дома (Леруа Мерлен, ОБИ, Касторама, ИКЕА и т.п.)',
        2 => 'В магазинах-специалистах (специализированный магазин сантехники, магазин обоев и т.п.)',
        3 => 'В интернете',
        4 => 'В небольших магазинах «у дома»',
        5 => 'На строительных рынках',
		6 => 'Другое (укажите, что именно)',
	];

	// Где Вы покупаете / планируете покупать товары для ремонта / строительства?
	public static $L_MONEY = [
		1 => 'На питание денег хватает, но покупка одежды вызывает затруднение',
		2 => 'Денег хватает на питание, одежду, мелкую технику, но крупную бытовую технику сейчас купить было бы трудно',
		3 => 'Денег на жизнь хватает, но новый автомобиль купить бы не смогли',
		4 => 'Достаточно состоятельны, чтобы купить автомобиль, но такие крупные приобретения как квартира или дом купить бы не смогли без привлечения кредита или ипотеки',
		5 => 'Можем позволить себе любую покупку',
		6 => 'Затрудняюсь ответить / Не хочу отвечать',
	];

	// Где Вы покупаете / планируете покупать товары для ремонта / строительства?
	public static $L_DISTANCE = [
		1 => 'До 15 км',
		2 => '15 – 30 км',
		3 => '30 – 45 км',
		4 => '45 – 60 км',
		5 => 'Более 60 км',
	];

	// Какие работы Вы планируете выполнять в ходе вашего ремонта?
	public static $L_TYPE_OF_REPAIR = [
		1 => 'Черновые работы',
		2 => 'Отделочные работы',
		3 => 'Декоративные работы',
	];

	public function behaviors()
	{
		return [
			'TimestampBehavior' => [
				'class' => TimestampBehavior::className(),
			],
		];
	}

	public function rules()
	{
		return [

		];
	}

	public function scenarios()
	{
		$scenarios = parent::scenarios();
		$scenarios['create'] = [];
		$scenarios['update'] = [];
		return $scenarios;
	}

	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'session_id' => 'Session ID',
			'status' => 'Статус',
			'sex' => 'Укажите Ваш пол:',
			'city_id' => 'Город:',
			'city_other' => 'Город: другой',
			'age' => 'Сколько Вам полных лет?',
			'family' => 'Как бы Вы могли описать Ваше семейное положение?',
			'children' => 'Есть ли у Вас дети, проживающие вместе с Вами?',
			'repair_status' => 'Какое утверждение лучше всего описывает Вашу ситуацию?',
			'repair_when_finish' => 'Когда Вы планируете завершить ремонт / строительство?',
			'repairObject' => 'Какой объект Вы ремонтируете / строите или планируете ремонтировать / строить?',
			'repair_object_other' => 'Объект ремонта: другое',
			'have_cottage' => 'Скажите, есть ли у Вас дача?',
			'plan_cottage_works' => 'Планируете ли Вы в этом году проводить какие-то работы на даче: посадка растений, строительство или обустройство (в т.ч. баня, беседка, зона отдыха, качели, бассейн и т.п.)?',
			'who_worker' => 'Кто выполняет или будет выполнять работы по строительству / ремонту?',
			'who_chooser' => 'Кто принимает или будет принимать решения по выбору товаров для строительства и ремонта?',
			'who_buyer' => 'Кто покупает или будет покупать товары для строительства и ремонта?',
			'shop_name' => 'В каком магазине Леруа Мерлен Вам будет удобно получать вознаграждение за участие в проекте?',
			'fio' => 'Как к Вам обращаться при звонке?',
			'phone' => 'Телефон для связи:',
			'email' => 'Электронная почта:',
			'created_at' => 'Создана:',
			'money' => 'Финансовое положение:',
			'distance' => 'Расстояние до магазина:',
			'typeOfRepair' => 'Планируемые работы:',
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getCity()
	{
		return $this->hasOne(City::className(), ['id' => 'city_id']);
	}

	public function getRepairObject()
	{
		return $this->hasMany(ObjectRepair::className(), ['id' => 'object_repair_id'])
			->viaTable('{{%invite_object_repair}}', ['invite_id' => 'id']);
	}

	public function getRepairObjectsText()
	{
		$objects = ArrayHelper::getColumn($this->repairObject, 'name');

		if (!empty($this->repair_object_other)) {
			$objects[] = 'Другое: ' . $this->repair_object_other;
		}

		return $objects;
	}

	public function getTypeOfRepair()
	{
		return $this->getRelatedList('{{%invite_type_repair}}', 'type_repair_id', self::$L_TYPE_OF_REPAIR);
	}

	private function getRelatedList($table, $column, $valuesList, $otherValue = null)
	{
		$listQuery = (new Query())->select($column)->from($table)->where(['invite_id' => $this->id])->column();
		$otherIndex = ($otherValue !== null) ? key(array_slice($valuesList, -1, 1, true)) : null;

		return ArrayHelper::getColumn($listQuery, function($value) use ($valuesList, $otherValue, $otherIndex) {
			return ArrayHelper::getValue($valuesList, $value)
				. ($otherIndex !== null && $value === $otherIndex ? ': ' . $otherValue : '');
		});
	}

	public function approveForRegistration()
	{
		try {
			$this->status = self::STATUS_EMAIL_SENT;
			if ($this->save(true, ['status'])) {
				// Вызываем событие
				\Yii::$app->trigger(AppEvents::EVENT_REGISTRATION_LINK_SEND, new Event(['sender' => $this]));
				return true;
			}
			else {
				return false;
			}
		}
		catch (\Exception $e) {
			return false;
		}
	}
}

