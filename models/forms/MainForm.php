<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;

/**
 * MainForm is the model behind the main form.
 * @package app\models\forms
 */
class MainForm extends Model
{
    /** @var string */
    public $iin;

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            ['iin', 'trim'],
            ['iin', 'required', 'message' => 'Поле обязательно к заполнению'],
            ['iin', 'match', 'pattern' => '/^\d+$/i', 'message' => 'Некорректные символы в ИИН/БИН'],
            ['iin', 'string', 'length' => 12, 'notEqual' => 'Некорректная длина ИИН/БИН'],
            ['iin', 'validateIin'],
        ];
    }

    /**
     * @param $attribute
     */
    public function validateIin($attribute)
    {
        $nn = (string)$this->$attribute;
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
            $this->addError($attribute, 'Введенное значение не соответствует ИИН/БИН');
        }
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return [
            'iin' => 'ИИН/БИН',
        ];
    }
}
