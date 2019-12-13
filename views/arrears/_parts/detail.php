<?php

use app\models\db\Arrears;
use yii\web\View;

/* @var $this View */
/* @var $answer Arrears */
?>
<table class="table">
    <tr>
        <td>Наименование налогоплательщика</td>
        <td><?= $answer->nameRu ?></td>
    </tr>
    <tr>
        <td>ИИН/БИН налогоплательщика</td>
        <td><?= $answer->iinBin ?></td>
    </tr>
    <tr>
        <td>Всего задолженности (тенге)</td>
        <td><?= number_format($answer->totalArrear, 2, '.', ' ') ?></td>
    </tr>
</table>

<table class="table table-bordered table-hover">
    <tr>
        <td>Итого задолженности в бюджет (тенге)</td>
        <td><?= number_format($answer->totalTaxArrear, 2, '.', ' ') ?></td>
    </tr>
    <tr>
        <td>Задолженность по обязательным пенсионным взносам, обязательным профессиональным пенсионным взносам</td>
        <td><?= number_format($answer->pensionContributionArrear, 2, '.', ' ') ?></td>
    </tr>
    <tr>
        <td>Задолженность по отчислениям и (или) взносам на обязательное социальное медицинское страхование</td>
        <td><?= number_format($answer->socialContributionArrear, 2, '.', ' ') ?></td>
    </tr>
    <tr>
        <td>Задолженность по социальным отчислениям</td>
        <td><?= number_format($answer->socialHealthInsuranceArrear, 2, '.', ' ') ?></td>
    </tr>
    <tr>
        <td>Суммы, начисленные по результатам налоговой проверки, находящиеся на стадии обжалования и обжалованные</td>
        <td><?= number_format($answer->appealledAmount, 2, '.', ' ') ?></td>
    </tr>
    <tr>
        <td>Суммы, по которым изменены сроки уплаты</td>
        <td><?= number_format($answer->modifiedTermsAmount, 2, '.', ' ') ?></td>
    </tr>
    <tr>
        <td>Суммы, по которым применена реабилитационная процедура</td>
        <td><?= number_format($answer->rehabilitaionProcedureAmount, 2, '.', ' ') ?></td>
    </tr>
</table>

<?php if (!empty($answer->taxOrgInfos)) : foreach ($answer->taxOrgInfos as $taxOrgInfo) : ?>
    <hr>
    <p>Таблица задолженностей по органам государственных доходов</p>

    <div class="panel panel-default">
        <div class="panel-heading">
            <?= $taxOrgInfo->nameRu ?> Код ОГД <strong><?= $taxOrgInfo->charCode ?></strong>
        </div>
        <div class="panel-body">
            <div>По состоянию на <?= Yii::$app->formatter->asDate($taxOrgInfo->reportAcrualDate, 'php:d.m.Y') ?></div>
            <div>Всего задолженности: <?= number_format($taxOrgInfo->totalArrear, 2, '.', ' ') ?></div>
            <hr>

            <table class="table table-bordered table-hover">
                <tr>
                    <td>Итого задолженности в бюджет</td>
                    <td><?= number_format($taxOrgInfo->totalTaxArrear, 2, '.', ' ') ?></td>
                </tr>
                <tr>
                    <td>Задолженность по обязательным пенсионным взносам, обязательным профессиональным пенсионным
                        взносам
                    </td>
                    <td><?= number_format($taxOrgInfo->pensionContributionArrear, 2, '.', ' ') ?></td>
                </tr>
                <tr>
                    <td>Задолженность по отчислениям и (или) взносам на обязательное социальное медицинское
                        страхование
                    </td>
                    <td><?= number_format($taxOrgInfo->socialContributionArrear, 2, '.', ' ') ?></td>
                </tr>
                <tr>
                    <td>Задолженность по социальным отчислениям</td>
                    <td><?= number_format($taxOrgInfo->socialHealthInsuranceArrear, 2, '.', ' ') ?></td>
                </tr>
                <tr>
                    <td>Суммы, начисленные по результатам налоговой проверки, находящиеся на стадии обжалования и
                        обжалованные
                    </td>
                    <td><?= number_format($taxOrgInfo->appealledAmount, 2, '.', ' ') ?></td>
                </tr>
                <tr>
                    <td>Суммы, по которым изменены сроки уплаты</td>
                    <td><?= number_format($taxOrgInfo->modifiedTermsAmount, 2, '.', ' ') ?></td>
                </tr>
                <tr>
                    <td>Суммы, по которым применена реабилитационная процедура</td>
                    <td><?= number_format($taxOrgInfo->rehabilitaionProcedureAmount, 2, '.', ' ') ?></td>
                </tr>
            </table>

            <?php if (!empty($taxOrgInfo->taxPayerInfos)) : foreach ($taxOrgInfo->taxPayerInfos as $taxPayerInfo) : ?>
                <hr>
                <p>Таблица задолженностей по налогоплательщику и его структурным подразделениям</p>

                <table class="table table-bordered table-hover" id="table5_0">
                    <tr>
                        <td>Наименование налогоплательщика:</td>
                        <td><?= $taxPayerInfo->nameRu ?></td>
                    </tr>
                    <tr>
                        <td>ИИН/БИН налогоплательщика:</td>
                        <td><?= $taxPayerInfo->iinBin ?></td>
                    </tr>
                    <tr>
                        <td>Всего задолженности:</td>
                        <td><?= number_format($taxPayerInfo->totalArrear, 2, '.', ' ') ?></td>
                    </tr>
                </table>

                <?php if (!empty($taxPayerInfo->bccArrearsInfos)) : ?>
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>КБК</th>
                            <th>Задолженность по платежам, учет по которым ведется в органах государственных
                                доходов
                            </th>
                            <th>Задолженность по сумме пени</th>
                            <th>Задолженность по сумме процентов</th>
                            <th>Задолженность по сумме штрафа</th>
                            <th>Всего задолженности</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($taxPayerInfo->bccArrearsInfos as $bccArrearsInfo) : ?>
                            <tr>
                                <td><?= $bccArrearsInfo->bcc ?> <?= $bccArrearsInfo->bccNameRu ?></td>
                                <td><?= number_format($bccArrearsInfo->taxArrear, 2, '.', ' ') ?></td>
                                <td><?= number_format($bccArrearsInfo->poenaArrear, 2, '.', ' ') ?></td>
                                <td><?= number_format($bccArrearsInfo->percentArrear, 2, '.', ' ') ?></td>
                                <td><?= number_format($bccArrearsInfo->fineArrear, 2, '.', ' ') ?></td>
                                <td><?= number_format($bccArrearsInfo->totalArrear, 2, '.', ' ') ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>

            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>
<?php endif; ?>
