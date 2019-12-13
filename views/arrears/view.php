<?php

use yii\helpers\Html;
use yii\web\YiiAsset;

/* @var $this yii\web\View */
/* @var $model app\models\db\Arrears */

$this->title = $model->iinBin;
$this->params['breadcrumbs'][] = ['label' => 'Сведения об отсутствии (наличии) задолженности', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

YiiAsset::register($this);
?>
<div class="arrears-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Назад', ['index'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data'  => [
                'confirm' => 'Вы уверены, что хотите удалить запись?',
                'method'  => 'post',
            ],
        ]) ?>
    </p>


    <?= $this->render('_parts/detail', [
        'answer' => $model,
    ]) ?>

</div>
