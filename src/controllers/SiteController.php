<?php

namespace app\controllers;

use app\components\NullUser;
use app\models\forms\SaasuSettingsForm;
use app\models\forms\TimeSheetSettingsForm;
use app\models\forms\TogglSettingsForm;
use app\models\forms\XeroSettingsForm;
use app\models\forms\ZipBooksSettingsForm;
use Yii;
use yii\base\Exception;
use yii\filters\auth\HttpBasicAuth;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii2mod\settings\actions\SettingsAction;

/**
 * Site controller.
 */
class SiteController extends Controller
{

    public $enableCsrfValidation = false;

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
            'timesheet-settings' => [
                'class' => SettingsAction::class,
                'view' => 'timesheet-settings',
                'modelClass' => TimeSheetSettingsForm::class,
            ],
            'toggl-settings' => [
                'class' => SettingsAction::class,
                'view' => 'toggl-settings',
                'modelClass' => TogglSettingsForm::class,
            ],
            'saasu-settings' => [
                'class' => SettingsAction::class,
                'view' => 'saasu-settings',
                'modelClass' => SaasuSettingsForm::class,
            ],
            'zipbooks-settings' => [
                'class' => SettingsAction::class,
                'view' => 'zipbooks-settings',
                'modelClass' => ZipBooksSettingsForm::class,
            ],
            'xero-settings' => [
                'class' => SettingsAction::class,
                'view' => 'xero-settings',
                'modelClass' => XeroSettingsForm::class,
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
        $toggl = Json::decode(Yii::$app->settings->get('app', 'toggl'));
        $times = Yii::$app->timeSheet->getTimes($toggl);
        $staffTimes = Yii::$app->timeSheet->getStaffTimes($times);
        $totals = Yii::$app->timeSheet->getTotals($times);
        return $this->render('index', [
            'toggl' => $toggl,
            'times' => $times,
            'staffTimes' => $staffTimes,
            'totals' => $totals,
        ]);
    }

    /**
     * Imports data from Toggl
     *
     * @return \yii\web\Response
     * @throws \Exception
     */
    public function actionImportToggl()
    {
        Yii::$app->settings->set('app', 'toggl', Json::encode(Yii::$app->toggl->import(Yii::$app->timeSheet->staff)));
        return $this->redirect(Url::home());
    }

    /**
     * Imports data from Upwork
     *
     * @return \yii\web\Response
     * @throws \Exception
     */
    public function actionImportUpwork()
    {
        Yii::$app->settings->set('app', 'upwork', Json::encode(Yii::$app->upwork->import(Yii::$app->timeSheet->staff)));
        return $this->redirect(Url::home());
    }

    /**
     * Exports data to Saasu
     *
     * @return \yii\web\Response
     * @throws \yii\base\Exception
     */
    public function actionExportSaasu()
    {
        // create sale invoices
        $toggl = Json::decode(Yii::$app->settings->get('app', 'toggl'));
        $times = Yii::$app->timeSheet->getTimes($toggl);
        foreach ($times as $pid => $_times) {
            Yii::$app->saasu->createSaleInvoice($pid, $_times);
        }
        // create purchase invoices
        $staffTimes = Yii::$app->timeSheet->getStaffTimes($times);
        foreach ($staffTimes as $sid => $_times) {
            Yii::$app->saasu->createPurchaseInvoice($sid, $_times);
        }

        Yii::$app->settings->set('TogglSettingsForm', 'startDate', date('Y-m-d'));
        return $this->redirect(['/site/import-toggl']);
    }

    /**
     * Exports data to Saasu
     *
     * @return \yii\web\Response
     */
    public function actionExportZipbooks()
    {
        // create invoices
        $toggl = Json::decode(Yii::$app->settings->get('app', 'toggl'));
        $times = Yii::$app->timeSheet->getTimes($toggl);
        foreach ($times as $pid => $_times) {
            Yii::$app->zipBooks->createInvoice($pid, $_times);
        }
        // create expenses
        $staffTimes = Yii::$app->timeSheet->getStaffTimes($times);
        foreach ($staffTimes as $sid => $_times) {
            Yii::$app->zipBooks->createExpense($sid, $_times);
        }

        Yii::$app->settings->set('TogglSettingsForm', 'startDate', date('Y-m-d'));
        return $this->redirect(['/site/import-toggl']);
    }

    /**
     * Exports data to Xero
     *
     * @return \yii\web\Response
     * @throws \XeroPHP\Remote\Exception
     */
    public function actionExportXero()
    {
        // create sale invoices
        $toggl = Json::decode(Yii::$app->settings->get('app', 'toggl'));
        $times = Yii::$app->timeSheet->getTimes($toggl);
        foreach ($times as $pid => $_times) {
            Yii::$app->xero->createSaleInvoice($pid, $_times);
        }
        // create purchase invoices
        $staffTimes = Yii::$app->timeSheet->getStaffTimes($times);
        foreach ($staffTimes as $sid => $_times) {
            Yii::$app->xero->createPurchaseInvoice($sid, $_times);
        }

        Yii::$app->settings->set('TogglSettingsForm', 'startDate', date('Y-m-d'));
        return $this->redirect(['/site/import-toggl']);
    }

    /**
     * Xero OAuth2
     */
    public function actionXeroAuth($code = null, $state = null)
    {
        $provider = new \Calcinai\OAuth2\Client\Provider\Xero([
            'clientId' => Yii::$app->settings->get('XeroSettingsForm', 'consumerKey'),
            'clientSecret' => Yii::$app->settings->get('XeroSettingsForm', 'consumerSecret'),
            'redirectUri' => Url::to(['site/xero-auth'], 'https'),
        ]);

        if (!$code) {
            // If we don't have an authorization code then get one
            $authUrl = $provider->getAuthorizationUrl([
                'scope' => 'openid email profile accounting.transactions',
            ]);
            Yii::$app->session->set('oauth2state', $provider->getState());
            $this->redirect($authUrl);

            // Check given state against previously stored one to mitigate CSRF attack
        } elseif (empty($state) || ($state !== Yii::$app->session->get('oauth2state'))) {
            Yii::$app->session->remove('oauth2state');
            throw new Exception('Invalid state');
        } else {
            // Try to get an access token (using the authorization code grant)
            $accessToken = $provider->getAccessToken('authorization_code', [
                'code' => $code
            ]);
            //If you added the openid/profile scopes you can access the authorizing user's identity.
            //$identity = $provider->getResourceOwner($token);
            //debug($identity);
            //Get the tenants that this user is authorized to access
            $tenants = $provider->getTenants($accessToken);
            //debug($tenants);

            Yii::$app->settings->set('XeroSettingsForm', 'accessToken', Json::encode($accessToken->jsonSerialize()));
            //Yii::$app->settings->set('XeroSettingsForm', 'identity', $identity);
            Yii::$app->settings->set('XeroSettingsForm', 'tenantId', $tenants[0]->tenantId);
            return $this->redirect(Url::home());
        }
    }

    /**
     * Dumps the variables.
     *
     * @return string
     */
    public function actionDump()
    {
        $toggl = Json::decode(Yii::$app->settings->get('app', 'toggl'));
        $times = Yii::$app->timeSheet->getTimes($toggl);
        $staffTimes = Yii::$app->timeSheet->getStaffTimes($times);
        $totals = Yii::$app->timeSheet->getTotals($times);
        return $this->render('dump', [
            'toggl' => $toggl,
            'times' => $times,
            'staffTimes' => $staffTimes,
            'totals' => $totals,
        ]);
    }

    /**
     *
     */
    public function actionGenEnv()
    {
        $dotEnv = '';
        foreach ($_ENV as $key => $value) {
            $dotEnv .= "$key=$value\n";
        }
        echo "<pre>";
        echo $dotEnv;
        exit();
    }
}
