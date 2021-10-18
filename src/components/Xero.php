<?php

namespace app\components;

use Calcinai\OAuth2\Client\XeroTenant;
use League\OAuth2\Client\Token\AccessToken;
use XeroPHP\Application;
use XeroPHP\Models\Accounting;
use Yii;
use yii\base\Component;
use yii\helpers\Json;

/**
 * XeroApi
 *
 * @property Application $xero
 */
class Xero extends Component
{
    const URL = 'https://go.xero.com/';

    /**
     * @var AccessToken
     */
    public $accessToken;

    /**
     * @var string
     */
    public $tenantId;

    /**
     * @var int
     */
    public $saleAccountId;

    /**
     * @var int
     */
    public $purchaseAccountId;

    /**
     * @var Application
     */
    private $_xero;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->accessToken = new AccessToken(Json::decode(Yii::$app->settings->get('XeroSettingsForm', 'accessToken')));
        foreach (['tenantId', 'saleAccountId', 'purchaseAccountId'] as $key) {
            $value = Yii::$app->settings->get('XeroSettingsForm', $key);
            if ($value) {
                $this->$key = $value;
            }
        }
    }

    /**
     * @return Application
     */
    public function getXero()
    {
        if (!$this->_xero) {
            $accessToken = $this->accessToken;
            if ($accessToken->hasExpired()) {
                $accessToken = $this->accessToken->getRefreshToken();
                Yii::$app->settings->set('XeroSettingsForm', 'accessToken', Json::encode($accessToken->jsonSerialize()));
            }
            $this->_xero = new Application($accessToken->getToken(), $this->tenants[0]->tenantId);
        }
        return $this->_xero;
    }

    /**
     * @param string $model
     * @return array
     * @throws \XeroPHP\Remote\Exception
     */
    public function getAllPages($model = 'Accounting\\Invoice')
    {
        $page = 1;
        $invoices = [];

        while (true) {
            $invoiceList = $this->xero->load($model)->page($page)->execute();
            if (!$invoiceList) {
                break;
            }
            foreach ($invoiceList as $invoice) {
                $invoices[] = $invoice;
            }

            $page++;
        }

        return $invoices;
    }

    /**
     * @param string $signature
     * @return bool
     */
    public function validateWebhookSignature($signature)
    {
        return $signature == base64_encode(hash_hmac('sha256', Yii::$app->request->rawBody, $this->webhookKey, true));
    }

    /**
     * @param string $pid
     * @param array $times
     * @throws \XeroPHP\Remote\Exception
     */
    public function createSaleInvoice($pid, $times)
    {
        //debug($this->xero->loadByGUID('Accounting\\Invoice', 'bd87e11d-c85c-4c10-87a3-fddeddc3ec34')); die;
        $project = Yii::$app->timeSheet->projects[$pid];
        $invoice = (new Accounting\Invoice($this->xero))
            ->setType('ACCREC')
            ->setContact((new Accounting\Contact($this->xero))->setContactID($project['xero_contact_id']))
            ->setStatus('SUBMITTED')// SUBMITTED|AUTHORISED|DRAFT
            ->setDate(new \DateTime('now'))
            ->setDueDate(new \DateTime('+7 days'))
            ->setReference("Development for {$project['name']}");
        foreach ($times as $sid => $dates) {
            foreach ($dates as $date => $tasks) {
                foreach ($tasks as $task) {
                    $staff = Yii::$app->timeSheet->staff[$task['sid']];
                    $lineItem = (new Accounting\Invoice\LineItem($this->xero))
                        ->setDescription(date('Y-m-d', strtotime($task['date'])) . ' - ' . $task['description'])
                        ->setQuantity(round($task['hours'], 2))
                        ->setUnitAmount(round($task['sell'] / (Yii::$app->timeSheet->getProjectTaxRate($pid) + 1), 2))
                        ->setAccountCode($staff['xero_sale_account_id'])
                        ->setItemCode($staff['xero_item_code'])
                        ->setTaxType($project['xero_tax_code']); // OUTPUT|BASEXCLUDED
                    $invoice->addLineItem($lineItem);
                }
            }
        }
        $invoice->save();
        //$invoice->sendEmail(); // easier to send it manually
    }

    /**
     * @param string $sid
     * @param array $times
     * @throws \XeroPHP\Remote\Exception
     */
    public function createPurchaseInvoice($sid, $times)
    {
        //debug($this->xero->loadByGUID('Accounting\\Invoice', 'b87932ae-1272-4b01-b67e-c9e6616fadf3')); die;
        $staff = Yii::$app->timeSheet->staff[$sid];
        if (!isset($staff['xero_contact_id'])) {
            return;
        }
        $invoice = (new Accounting\Invoice($this->xero))
            ->setType('ACCPAY')
            ->setContact((new Accounting\Contact($this->xero))->setContactID($staff['xero_contact_id']))
            ->setStatus('SUBMITTED')// SUBMITTED|AUTHORISED|DRAFT
            ->setDate(new \DateTime('now'))
            ->setDueDate(new \DateTime('+7 days'))
            ->setReference("Development by {$staff['name']}");
        foreach ($times as $pid => $dates) {
            foreach ($dates as $date => $tasks) {
                foreach ($tasks as $task) {
                    $project = Yii::$app->timeSheet->projects[$task['pid']];
                    $lineItem = (new Accounting\Invoice\LineItem($this->xero))
                        ->setDescription(date('Y-m-d', strtotime($task['date'])) . ' ' . $project['name'] . ' - ' . $task['description'])
                        ->setQuantity(round($task['hours'], 2))
                        ->setUnitAmount(round($task['cost'] / (Yii::$app->timeSheet->getStaffTaxRate($sid, $pid) + 1), 2))
                        ->setItemCode($staff['xero_item_code'])
                        ->setTaxType($staff['xero_tax_code'])
                        ->setAccountCode($staff['xero_purchase_account_id']);
                    $invoice->addLineItem($lineItem);
                }
            }
        }
        $invoice->save();
        //$invoice->sendEmail(); // only for ACCREC not ACCPAY
    }

}
