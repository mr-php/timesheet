<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\Exception;

/**
 * Class ZipBooks
 * @package app\components
 */
class ZipBooks extends Component
{

    /**
     * @var string
     */
    public $fromEmail;

    /**
     * @var string
     */
    public $invoiceEmailSubject;

    /**
     * @var string
     */
    public $invoiceEmailBody;

    /**
     * @var string
     */
    public $expenseEmailSubject;

    /**
     * @var string
     */
    public $expenseEmailBody;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $settings = ['fromEmail', 'invoiceEmailSubject', 'invoiceEmailBody', 'expenseEmailSubject', 'expenseEmailBody'];
        foreach ($settings as $key) {
            $value = Yii::$app->settings->get('ZipBooksSettingsForm', $key);
            if ($value) {
                $this->$key = $value;
            }
        }
    }

    /**
     * @param string $pid
     * @param array $times
     */
    public function createInvoice($pid, $times)
    {
        /** @see https://developer.zipbooks.com/#api-Invoices-PostInvoice */
        $invoice = [
            'customer' => '',
            'number' => '',
            'date' => '',
            'discount' => '',
            'accept_credit_cards' => '',
            'terms' => '',
            'notes' => '',
            'logo_filename' => '',
            'lineItems' => [],
        ];
        $i = 1;
        $invoice['lineItems'][$i] = [
            'type' => '',
            'name' => '',
            'notes' => '',
            'rate' => '',
            'quantity' => '',
        ];
        $this->_call('invoices', $invoice);

        /** @see https://developer.zipbooks.com/#api-Invoices-postInvoiceSend */
        $this->_call('invoices/:id/send', [
            'send_to' => '',
            'subject' => '',
            'message' => '',
            'bcc' => true,
            'pdf' => true,
        ]);
    }

    /**
     * @param string $sid
     * @param array $times
     * @throws Exception
     */
    public function createExpense($sid, $times)
    {
        /** @see https://developer.zipbooks.com/#api-Expenses-PostExpense */
        $this->_call('expenses', [
            'amount' => '',
            'date' => '',
            'customer_id' => '',
            'name' => '',
            'category' => '',
            'note' => '',
            'image_filename' => '',
        ]);
    }

    /**
     * @param $action
     * @param array $params
     * @return mixed
     */
    private function _call($action, $params = [])
    {
        // https://developer.zipbooks.com/#api-Authentication-PostAuthLogin
        //https://api.zipbooks.com/v1/auth/login
        $client = new \GuzzleHttp\Client();

        if (!$this->token) {
            $response = $client->request('POST', 'https://api.zipbooks.com/v1/auth/login', [
                'json' => ['email' => $this->email, 'password' => $this->password],
            ])->getBody()->getContents();
            $this->token = $response->token;
        }

        if ($this->token) {
            $response = $client->request('POST', 'https://api.zipbooks.com/v1/auth/check')->getBody()->getContents();
        }

        $url = 'https://api.zipbooks.com/v1/' . $action;
        return $client->request('POST', $url, [
            'json' => $params,
            'auth' => ['user', 'pass']

        ], [
            'headers' => ['Authorization' => 'Bearer ' . $this->token],
        ])->getBody()->getContents();
    }

}