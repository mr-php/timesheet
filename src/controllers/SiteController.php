<?php

namespace app\controllers;

use app\components\NullUser;
use Yii;
use yii\filters\auth\HttpBasicAuth;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * Site controller.
 */
class SiteController extends Controller
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        Yii::$app->user->enableSession = false;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        if ($this->action->id != 'error') {
            $behaviors['basicAuth'] = [
                'class' => HttpBasicAuth::className(),
                'auth' => function ($username, $password) {
                    if ($username == 'admin' && $password == getenv('APP_PASSWORD')) {
                        return new NullUser();
                    }
                    return null;
                },
            ];
        }
        return $behaviors;
    }

    /**
     * @inheritdoc
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
        $toggl = Yii::$app->cache->get('toggl');
        $times = Yii::$app->timeSheet->getTimes($toggl);
        $totals = Yii::$app->timeSheet->getTotals($times);
        return $this->render('index', [
            'toggl' => $toggl,
            'times' => $times,
            'totals' => $totals,
        ]);
    }

    /**
     * Imports data from Toggl
     *
     * @return \yii\web\Response
     */
    public function actionImportToggl()
    {
        Yii::$app->cache->set('toggl', Yii::$app->toggl->import(Yii::$app->timeSheet->staff));
        return $this->redirect(Url::home());
    }

    /**
     * Exports data to Saasu
     *
     * @return \yii\web\Response
     */
    public function actionExportSaasu()
    {
        $times = Yii::$app->timeSheet->getTimes(Yii::$app->cache->get('toggl'));
        foreach ($times as $pid => $_times) {
            Yii::$app->saasu->createInvoice($pid, $_times);
        }
        Yii::$app->cache->set('lastInvoiceDate', date('Y-m-d'));
        return $this->redirect(['/site/import-toggl']);
    }

}
