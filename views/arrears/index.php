<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ArrearsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Сведения об отсутствии (наличии) задолженности';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="arrears-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Сделать новый запрос', ['/site/index'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],

            'iinBin',
            'nameRu',

            [
                'attribute' => 'totalArrear',
                'format'    => ['decimal', 2],
                'filter'    => false,
            ],

            [
                'attribute' => 'sendTime',
                'format'    => ['date', 'php:d.m.Y H:i:s'],
                'filter'    => false,
            ],

            [
                'class'    => 'yii\grid\ActionColumn',
                'template' => '{view} {delete}',
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
