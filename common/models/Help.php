<?php
namespace common\models;

use common\components\ActiveRecord;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class Help
 * @package common\models
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $default
 * @property integer $updated_at
 * @property HelpRole[] $helpRoles
 * @property Presentation[] $presentations
 *
 * @mixin HelpQuery
 */
class Help extends ActiveRecord
{
    public static function find()
    {
        return new HelpQuery(get_called_class());
    }

    public static function getList(){
        $helps = self::find()->allHelp()->all();
        $list = [];

        foreach ($helps as $help){
            $list[$help->id] = $help->title;
        }
        return $list;
    }

    public function getPresentations()
    {
        return $this->hasMany(Presentation::className(), ['help_id' => 'id']);
    }

    public function getHelpRoles()
    {
        return $this->hasMany(HelpRole::className(), ['help_id' => 'id']);
    }

    public function setRoles($data)
    {
        if (($data != null) and (is_array($data))) {
            $_items = array();

            foreach ($data as $k => $item) {
                $itemModel = new HelpRole();
                $itemModel->scenario = 'create';
                $itemModel->role = $item;

                $_items[] = $itemModel;
            }
            $this->populateRelation('helpRoles', $_items);
        }
        else {
            $this->populateRelation('helpRoles', []);
        }
    }

    public function getRoles()
    {
        return ArrayHelper::getColumn( $this->helpRoles, 'role' );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['title', 'filter', 'filter' => 'trim'],
            ['title', 'required'],
            ['title', 'string', 'min' => 3, 'max' => 250],

            ['content', 'filter', 'filter' => 'trim'],

            ['default', 'in', 'range' => array_keys(Help::getDefaultList())],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['title', 'content', 'default', 'roles'];
        $scenarios['update'] = ['title', 'content', 'default', 'roles'];
        $scenarios['view'] = [];
        return $scenarios;
    }

    public function transactions()
    {
        return [
            'create' => self::OP_INSERT,
            'update' => self::OP_ALL,
        ];
    }

    public function attributeLabels()
    {
        return array(
            'title' => 'Заголовок страницы',
            'content' => 'Текст',
            'updated_at' => 'Последнее обновление',
            'default' => 'Страница "по-умолчанию"',
            'roles' => 'Роли, которые могут просматривать данную страницу',
        );
    }

    public static function getDefaultList()
    {
        return [
            0 => 'Нет',
            1 => 'Да',
        ];
    }

    public function init()
    {
        parent::init();
        $this->default = array_keys(Help::getDefaultList())[0];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->default == 1) {
            Yii::$app->db->createCommand()->update('{{%help}}', ['default'=>0], 'id <> :id', [':id'=>$this->getPrimaryKey()])->execute();
        }

        if ($this->isAttributeSafe('roles')) {
            $_helpRoles = $this->helpRoles;
            if (!$this->isNewRecord)
                $this->unlinkAll('helpRoles', true);

            foreach ($_helpRoles as $helpRole)
                $this->link('helpRoles', $helpRole);
        }
    }

    public function beforeDelete()
    {
        $res = parent::beforeDelete();

        if ($res)
            $this->unlinkAll('helpRoles', true);

        return $res;
    }
}