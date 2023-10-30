<?php
namespace common\models\settings;

use common\components\ActiveRecord;
use ReflectionClass;
use yii\base\Model;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * Модель для таблицы "{{Settings}}".
 *
 * Столбцы таблицы '{{Settings}}':
 * @property integer $id
 * @property string $name
 * @property string $rus_name
 * @property string $value
 * @property string $updated_at
 */
class Settings extends ActiveRecord
{
    /***
     * @return SettingsRewards
     */
    public static function SettingsRewards()
    {
        return Settings::getSettingByName('SettingsRewards');
    }

    public function rules()
    {
        return [
            ['name', 'required'],
            ['name', 'string', 'max' => 200],

            ['rus_name', 'required'],
            ['rus_name', 'string', 'max' => 200],
        ];
    }

    public function relations()
    {
        return array();
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Название',
            'rus_name' => 'Название',
            'updated_at' => 'Последнее обновление',
        );
    }

    /***
     * @param $name
     * @throws HttpException
     * @return Model
     */
    public static function getSettingByName($name)
    {
        /**@var $settingsModel Settings*/
        if (($settingsModel = self::findOne(['name' => $name])) === null) {
            throw new NotFoundHttpException('Ошибка при получении настройки');
        }

        /**@var $model Model*/
        $model = (new ReflectionClass('common\\models\\settings\\' .$settingsModel->name))->newInstance();
        $model->setScenario('update');

        if ($settingsModel->value != '')
            $model->attributes = unserialize($settingsModel->value);

        return $model;
    }
}