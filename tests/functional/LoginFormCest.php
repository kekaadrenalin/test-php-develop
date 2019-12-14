<?php

use app\models\User;

class LoginFormCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amOnRoute('site/login');
    }

    public function openLoginPage(FunctionalTester $I)
    {
        $I->see('Вход', 'h1');

    }

    // demonstrates `amLoggedInAs` method
    public function internalLoginById(FunctionalTester $I)
    {
        $I->amLoggedInAs(100);
        $I->amOnPage('/');
        $I->see('Выйти (admin)');
    }

    // demonstrates `amLoggedInAs` method
    public function internalLoginByInstance(FunctionalTester $I)
    {
        $I->amLoggedInAs(User::findByUsername('admin'));
        $I->amOnPage('/');
        $I->see('Выйти (admin)');
    }

    public function loginWithEmptyCredentials(FunctionalTester $I)
    {
        $I->submitForm('#login-form', []);
        $I->expectTo('see validations errors');
        $I->see('Логин cannot be blank.');
        $I->see('Пароль cannot be blank.');
    }

    public function loginWithWrongCredentials(FunctionalTester $I)
    {
        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'admin',
            'LoginForm[password]' => 'wrong',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Неверный логин или пароль.');
    }

    public function loginSuccessfully(FunctionalTester $I)
    {
        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'admin',
            'LoginForm[password]' => 'admin',
        ]);
        $I->see('Выйти (admin)');
        $I->dontSeeElement('form#login-form');              
    }
}