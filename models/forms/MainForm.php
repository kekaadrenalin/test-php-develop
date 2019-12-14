<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\components\validators\IinValidator;

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
            ['iin', IinValidator::class],
        ];
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
