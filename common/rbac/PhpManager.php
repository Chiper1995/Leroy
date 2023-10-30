<?php

namespace common\rbac;

use yii;
use yii\rbac\Item;
use yii\rbac\Permission;
use yii\rbac\Role;

class PhpManager extends yii\rbac\PhpManager
{
    // Не будем использовать
    public $assignmentFile = null;
    public $ruleFile = '@common/config/auth_roles.php';

    // Иерархию ролей расположим в файле auth.php
    public $itemFile = '@common/config/auth.php';

    public function init()
    {
        $this->itemFile = Yii::getAlias($this->itemFile);
        $this->assignmentFile = Yii::getAlias($this->assignmentFile);
        $this->ruleFile = Yii::getAlias($this->ruleFile);

        // Грузим все остальное
        $this->load();

        // Для гостей у нас и так роль по умолчанию guest.
        /*if (!Yii::$app->user->isGuest) {
            // Связываем роль, заданную в БД с идентификатором пользователя,
            $this->assign($this->getRole(Yii::$app->user->role), Yii::$app->user->id);
        }*/
    }

    protected function load()
    {
        $items = $this->loadFromFile($this->itemFile);
        $itemsMtime = @filemtime($this->itemFile);

        foreach ($items as $name => $item) {
            $class = $item['type'] == Item::TYPE_PERMISSION ? Permission::className() : Role::className();

            if (isset($item['ruleName'])) {
                $rule = new $item['ruleName'];
                $this->rules[$rule->name] = $rule;
            }

            $this->items[$name] = new $class([
                'name' => $name,
                'description' => isset($item['description']) ? $item['description'] : null,
                'ruleName' => isset($item['ruleName']) ? $item['ruleName'] : null,
                'data' => isset($item['data']) ? $item['data'] : null,
                'createdAt' => $itemsMtime,
                'updatedAt' => $itemsMtime,
            ]);
        }

        foreach ($items as $name => $item) {
            if (isset($item['children'])) {
                foreach ($item['children'] as $childName) {
                    if (isset($this->items[$childName])) {
                        $this->children[$name][$childName] = $this->items[$childName];
                    }
                }
            }
        }
    }

    protected function saveAssignments()
    {
        // Убираем реализацию сохранения
    }

    protected function saveRules()
    {
        // Убираем реализацию сохранения
    }
}