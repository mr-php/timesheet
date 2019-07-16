<?php

namespace app\components;

use XeroPHP\Application\PrivateApplication;
use XeroPHP\Models\Accounting;
use Yii;
use yii\base\Component;

/**
 * XeroApi
 *
 * @property PrivateApplication $xero
 */
class Xero extends Component
{
    const URL = 'https://go.xero.com/';

    /**
     * @var string
     */
    public $consumerKey;

    /**
     * @var string
     */
    public $consumerSecret;

    /**
     * @var string
     */
    public $publicKey;

    /**
     * @var string
     */
    public $privateKey;

    /**
     * @var string
     */
    public $userAgent;

    /**
     * @var
     */
    public $webhookKey;
    /**
     * @var int
     */
    public $saleAccountId;

    /**
     * @var int
     */
    public $purchaseAccountId;

    /**
     * @var string
     */
    public $fromEmail;

    /**
     * @var string
     */
    public $saleEmailSubject;

    /**
     * @var string
     */
    public $saleEmailBody;

    /**
     * @var string
     */
    public $purchaseEmailSubject;

    /**
     * @var string
     */
    public $purchaseEmailBody;

    /**
     * @var PrivateApplication
     */
    private $_xero;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $settings = ['consumerKey', 'consumerSecret', 'publicKey', 'privateKey', 'saleAccountId', 'purchaseAccountId',
            'fromEmail', 'saleEmailSubject', 'saleEmailBody', 'purchaseEmailSubject', 'purchaseEmailBody'];
        foreach ($settings as $key) {
            $value = Yii::$app->settings->get('XeroSettingsForm', $key);
            if ($value) {
                $this->$key = $value;
            }
        }
    }

    /**
     * @return PrivateApplication
     */
    public function getXero()
    {
        if (!$this->_xero) {
            $this->_xero = new PrivateApplication([
                'xero' => [
                    // API versions can be overridden if necessary for some reason.
                    //'core_version'     => '2.0',
                    //'payroll_version'  => '1.0',
                    //'file_version'     => '1.0'
                ],
                'oauth' => [
                    'callback' => null,
                    'consumer_key' => $this->consumerKey,
                    'consumer_secret' => $this->consumerSecret,
                    //If you have issues passing the Authorization header, you can set it to append to the query string
                    //'signature_location'    => \XeroPHP\Remote\OAuth\Client::SIGN_LOCATION_QUERY
                    //For certs on disk or a string - allows anything that is valid with openssl_pkey_get_(private|public)
                    'rsa_public_key' => $this->publicKey,
                    'rsa_private_key' => $this->privateKey,
                ],
                //These are raw curl options.  I didn't see the need to obfuscate these through methods
                'curl' => [
                    CURLOPT_USERAGENT => $this->userAgent,
                    CURLOPT_TIMEOUT => 120,
                    //Only for partner apps - unfortunately need to be files on disk only.
                    //CURLOPT_CAINFO          => 'certs/ca-bundle.crt',
                    //CURLOPT_SSLCERT         => 'certs/entrust-cert-RQ3.pem',
                    //CURLOPT_SSLKEYPASSWD    => '1234',
                    //CURLOPT_SSLKEY          => 'certs/entrust-private-RQ3.pem'
                ]
            ]);
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
            ->setStatus('DRAFT')
            ->setDate(new \DateTime('now'))
            ->setDueDate(new \DateTime('+7 days'))
            ->setReference("Development for {$project['name']}");
        foreach ($times as $sid => $dates) {
            foreach ($dates as $date => $tasks) {
                foreach ($tasks as $task) {
                    $staff = Yii::$app->timeSheet->staff[$task['sid']];
                    $lineItem = (new Accounting\Invoice\LineItem($this->xero))
                        ->setDescription(date('Y-m-d', strtotime($task['date'])) . ' ' . $staff['name'] . ' ' . Helper::formatHours($task['hours']) . ' - ' . $task['description'])
                        ->setQuantity(round($task['hours'], 2))
                        ->setUnitAmount(round($task['sell'], 2))
                        ->setAccountCode($staff['xero_sale_account_id'])
                        //->setTaxAmount(($amount - $discount) / ($product->quantity * 10))
                        ->setTaxType($project['xero_tax_code']); // OUTPUT|BASEXCLUDED
                    $invoice->addLineItem($lineItem);
                }
            }
        }
        $invoice->save();
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
        if (!isset($staff['xero_contact_uid'])) {
            return;
        }
        $invoice = (new Accounting\Invoice($this->xero))
            ->setType('ACCPAY')
            ->setContact((new Accounting\Contact($this->xero))->setContactID($staff['xero_contact_uid']))
            ->setStatus('DRAFT')
            ->setDate(new \DateTime('now'))
            ->setDueDate(new \DateTime('+7 days'))
            ->setReference("Development by {$staff['name']}");
        foreach ($times as $sid => $dates) {
            foreach ($dates as $date => $tasks) {
                foreach ($tasks as $task) {
                    $project = Yii::$app->timeSheet->projects[$task['pid']];
                    $lineItem = (new Accounting\Invoice\LineItem($this->xero))
                        ->setDescription(date('Y-m-d', strtotime($task['date'])) . ' ' . $project['name'] . ' ' . Helper::formatHours($task['hours']) . ' - ' . $task['description'])
                        ->setQuantity(round($task['hours'], 2))
                        ->setUnitAmount(round($task['cost'], 2))
                        //->setTaxAmount(($amount - $discount) / ($product->quantity * 10))
                        //->setTaxType('OUTPUT') // $staff['xero_tax_code']
                        ->setAccountCode($staff['saasu_purchase_account_uid']);
                    $invoice->addLineItem($lineItem);
                }
            }
        }
        $invoice->save();
    }
}
