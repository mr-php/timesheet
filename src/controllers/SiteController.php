<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * Site controller.
 */
class SiteController extends Controller
{
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
     * Renders the start page.
     *
     * @return string
     */
    public function actionIndex()
    {
        /** @var \app\components\TimeSheet $timeSheet */
        $timeSheet = Yii::$app->timeSheet;
        $times = $timeSheet->getTimes(Yii::$app->cache->get('toggl'));
        $totals = $timeSheet->getTotals($times);
        return $this->render('index', [
            'times' => $times,
            'totals' => $totals,
        ]);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionImportToggl()
    {
        Yii::$app->cache->set('toggl', Yii::$app->timeSheet->import());
        return $this->redirect(Url::home());
    }

    /**
     * @return \yii\web\Response
     */
    public function actionExportSaasu()
    {
        Yii::$app->timeSheet->export();
        Yii::$app->cache->set('lastInvoiceDate', date('Y-m-d'));
        return $this->redirect(Url::home());
    }

}
