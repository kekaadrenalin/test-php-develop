<?php

namespace app\controllers;

use Yii;
use Exception;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;

use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;

use app\components\ApiComponent;

use app\models\LoginForm;
use app\models\forms\MainForm;

use app\models\db\Arrears;
use app\models\db\BccArrearsInfo;
use app\models\db\TaxOrgInfo;
use app\models\db\TaxPayerInfo;

/**
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only'  => ['index', 'logout', 'save-answer'],
                'rules' => [
                    [
                        'actions' => ['index', 'logout', 'save-answer'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new MainForm;
        $answer = [];

        if (Yii::$app->request->isPjax) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $api = new ApiComponent($model->iin);

                $answer = $api->send();

                Yii::$app->session->set('currentAnswer', $answer);
            }
        }

        return $this->render('index', [
            'model'  => $model,
            'answer' => $answer,
        ]);
    }

    /**
     * Save current data.
     *
     * @return array
     */
    public function actionSaveAnswer()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $answer = Yii::$app->session->get('currentAnswer', []);
        if (!$answer) {
            return ['error' => 'empty answer'];
        }

        $transaction = Yii::$app->db->beginTransaction();
        $response = ['error' => 'unknown error'];

        try {
            $arrears = new Arrears;
            $arrears->attributes = $answer;

            $arrears->user_id = Yii::$app->user->id;
            $arrears->sendTime = Yii::$app->formatter->asDatetime((int)round($answer['sendTime'] / 1000), 'php:Y-m-d H:i:s');

            $validSave = $arrears->save();

            if ($validSave && !empty($answer['taxOrgInfo'])) {
                foreach ($answer['taxOrgInfo'] as $taxOrgInfo) {
                    $newTaxOrg = new TaxOrgInfo;
                    $newTaxOrg->attributes = $taxOrgInfo;
                    $newTaxOrg->reportAcrualDate = Yii::$app->formatter->asDatetime((int)round($taxOrgInfo['reportAcrualDate'] / 1000), 'php:Y-m-d H:i:s');

                    $newTaxOrg->link('arrear', $arrears);

                    if (!empty($taxOrgInfo['taxPayerInfo'])) {
                        foreach ($taxOrgInfo['taxPayerInfo'] as $taxPayerInfo) {
                            $newTaxPayer = new TaxPayerInfo;
                            $newTaxPayer->attributes = $taxPayerInfo;

                            $newTaxPayer->link('taxOrgInfo', $newTaxOrg);

                            if (!empty($taxPayerInfo['bccArrearsInfo'])) {
                                foreach ($taxPayerInfo['bccArrearsInfo'] as $bccArrearsInfo) {
                                    $newBccInfo = new BccArrearsInfo;
                                    $newBccInfo->attributes = $bccArrearsInfo;

                                    $newBccInfo->link('taxPayerInfo', $newTaxPayer);

                                    $transaction->commit();

                                    $response = [
                                        'success' => 'ok',
                                        'uri'     => Url::to(['arrears/index']),
                                    ];
                                }
                            }

                        }
                    }
                }
            }
        }
        catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            $transaction->rollBack();

            $response = ['error' => 'not valid answer'];
        }

        return $response;
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
