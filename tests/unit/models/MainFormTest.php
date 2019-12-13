<?php

namespace tests\unit\models;

use Yii;
use Codeception\Test\Unit;
use app\models\forms\MainForm;

class MainFormTest extends Unit
{
    private $model;

    public function testMainFormNotNumericIin()
    {
        $this->model = new MainForm([
            'iin' => 'aa',
        ]);

        expect_not($this->model->validate());
        expect($this->model->errors)->hasKey('iin');
    }

    public function testMainFormTooLongIin()
    {
        $this->model = new MainForm([
            'iin' => '11111111111111111111111111',
        ]);

        expect_not($this->model->validate());
        expect($this->model->errors)->hasKey('iin');
    }

    public function testMainFormTooShortIin()
    {
        $this->model = new MainForm([
            'iin' => '111111',
        ]);

        expect_not($this->model->validate());
        expect($this->model->errors)->hasKey('iin');
    }

    public function testMainFormNotValidIin()
    {
        $this->model = new MainForm([
            'iin' => '123456789012',
        ]);

        expect_not($this->model->validate());
        expect($this->model->errors)->hasKey('iin');
    }

    public function testMainFormValidIin()
    {
        $this->model = new MainForm([
            'iin' => '791005350297',
        ]);

        expect_that($this->model->validate());
        expect($this->model->errors)->hasntKey('iin');
    }

}
