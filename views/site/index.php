<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\forms\MainForm */

$this->title = 'Главная страница';
?>
<div class="site-index">
    <?php Pjax::begin() ?>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?= $form->field($model, 'iin')->input('number', [
                'placeholder' => '191005350297',
                'autofocus'   => true,
            ]) ?>

            <div class="form-group">
                <?= Html::submitButton('Получить результат', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>

            <?php ActiveForm::end() ?>
        </div>
    </div>

    <?php Pjax::end() ?>
</div>
