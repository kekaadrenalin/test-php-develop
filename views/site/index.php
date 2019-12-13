<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Pjax;

/* @var $model app\models\forms\MainForm */
/* @var $this View */
/* @var $answer array */

$this->title = 'Главная страница';

$js = <<<'JS'
var $pjaxBox = $('#pjax-box');
$(document)
  .on('pjax:send', function() {
    $pjaxBox.find('#progress-bar').show();
    $pjaxBox.find('#result-box').html('');
  })
  .on('pjax:complete', function() {
    $pjaxBox.find('#progress-bar').hide();
  });
$pjaxBox.on('click', '#btn-save', function(e) {
  e.preventDefault();
  $.get($(this).data('target'), function(r) {
    if (r.success === 'ok') {
      window.location = r.uri;
    } else {
      alert('Возникла ошибка');
    }
  })
})
JS;

$this->registerJs($js, View::POS_READY);
?>
<div class="site-index">
    <?php Pjax::begin(['id' => 'pjax-box']) ?>
    <div class="row">
        <div class="col-lg-4">
            <?php $form = ActiveForm::begin([
                'options' => [
                    'data' => ['pjax' => true],
                ],
            ]); ?>

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

    <div id="progress-bar" class="text-center" style="display: none">
        <img src="/img/loader.gif" alt="loader-img">
    </div>

    <?php if ($answer): ?>
        <div id="result-box" class="row">
            <div class="col-xs-12">
                <?= $this->render('_parts/detail', [
                    'answer' => $answer,
                ]) ?>

                <button id="btn-save" class="btn btn-success"
                        data-target="<?= Url::to(['site/save-answer']) ?>"
                        data-pjax="0">Сохранить
                </button>
            </div>
        </div>
    <?php endif; ?>

    <?php Pjax::end() ?>
</div>
