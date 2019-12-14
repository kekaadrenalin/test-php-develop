<?php

namespace app\components\validators;

use yii\base\Model;
use yii\validators\Validator;

/**
 * Class IinValidator
 * @package app\components\validators
 */
class IinValidator extends Validator
{
    /**
     * @param Model  $model
     * @param string $attribute
     */
    public function validateAttribute($model, $attribute)
    {
        $nn = (string)$model->$attribute;
        $bi_un_normal = [3, 4, 5, 6, 7, 8, 9, 10, 11, 1, 2];

        $a12 = 0;
        for ($number = 0; $number < 10; $number++) {
            $a12 += $nn[$number] * ($number + 1);
        }

        if ($a12 % 11 === 10) {
            $a12 = 0;
            for ($number = 0; $number < 11; $number++) {
                $a12 += $nn[$number] * $bi_un_normal[$number];
            }
        }

        $a12_local = $a12 % 11;

        if ($a12_local === 10 || (string)$a12_local !== $nn[11]) {
            $this->addError($model, $attribute, 'Введенное значение не соответствует ИИН/БИН');
        }
    }
}