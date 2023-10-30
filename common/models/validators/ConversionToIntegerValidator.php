<?php
namespace common\models\validators;

use yii\validators\Validator;

class ConversionToIntegerValidator extends Validator
{
    public $skipOnEmpty = true;

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        $model->$attribute = (int) $value;
    }
}