<?php

namespace tests\unit\models;

use app\components\ApiComponent;
use Codeception\Test\Unit;
use Exception;

class ApiTest extends Unit
{
    public function testNotInitApiWithEmptyIin()
    {
        $isValid = false;

        try {
            $this->constructEmpty(ApiComponent::class, ['iin' => '']);
        }
        catch (Exception $e) {
            $isValid = true;
        }

        $this->assertTrue($isValid);
    }

    public function testNotInitApiWithNotValidIin()
    {
        $isValid = false;

        try {
            $this->constructEmpty(ApiComponent::class, ['iin' => '1']);
        }
        catch (Exception $e) {
            $isValid = true;
        }

        $this->assertTrue($isValid);
    }

    public function testSending()
    {
        $isValid = true;
        $api = new ApiComponent('791005350297');

        try {
            $answer = $api->send();
        }
        catch (Exception $e) {
            $answer = [];
            $isValid = false;
        }

        $this->assertTrue($isValid);
        expect($answer)->hasntKey('captchaError');
    }
}
