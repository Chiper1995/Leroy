<?php
namespace frontend\models\registration;

use yii\base\Model;
use Yii;

/**
 * Works form
 */
class Works extends Model
{
    public $work_repair_list = [];

    public function attributeLabels()
    {
        return [
            'work_repair_list' => 'Планируемые работы',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['work_repair_list', 'required'],
        ];
    }
}
