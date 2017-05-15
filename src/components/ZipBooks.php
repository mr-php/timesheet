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
    public $logoFilename;

    /**
     * @var string
     */
    public $invoiceTerms;

    /**
     * @var string
     */
    public $invoiceNotes;

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
    public $expenseCategory;

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
        $settings = ['fromEmail', 'logoFilename', 'invoiceTerms', 'invoiceNotes', 'invoiceEmailSubject', 'invoiceEmailBody', 'expenseCategory', 'expenseEmailSubject', 'expenseEmailBody'];
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
        $project = Yii::$app->timeSheet->projects[$pid];

        /** @see https://developer.zipbooks.com/#api-Invoices-PostInvoice */
        $invoice = [
            'customer' => $project['zipbooks_customer'],
            'number' => uniqid(),
            'date' => date('Y-m-d'),
            //'discount' => 0,
            'accept_credit_cards' => false,
            'terms' => $this->invoiceTerms,
            'notes' => $this->invoiceNotes,
            'logo_filename' => $this->logoFilename,
            'lineItems' => [],
        ];
        $i = 0;
        foreach ($times as $sid => $dates) {
            foreach ($dates as $date => $tasks) {
                foreach ($tasks as $task) {
                    $i++;
                    $invoice['lineItems'][$i] = [
                        'type' => 'time-entry',
                        'name' => $task['description'],
                        'notes' => date('Y-m-d', strtotime($task['date'])) . ' ' . Yii::$app->timeSheet->staff[$task['sid']]['name'] . ' ' . Helper::formatHours($task['hours']),
                        'rate' => $task['sell'],
                        'quantity' => $task['hours'],
                    ];
                }
            }
        }
        $this->_call('invoices', $invoice);

        /** @see https://developer.zipbooks.com/#api-Invoices-postInvoiceSend */
        $this->_call('invoices/:id/send', [
            'send_to' => $project['email'],
            'subject' => strtr($this->invoiceEmailSubject, ['{project}' => $project['name']]),
            'message' => strtr($this->invoiceEmailBody, [
                '{project}' => $project['name'],
                '{times}' => Yii::$app->view->render('/site/_sale_times' . '', ['times' => $times]),
            ]),
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
        $staff = Yii::$app->timeSheet->staff[$sid];

        $amount = 0;
        foreach ($times as $sid => $dates) {
            foreach ($dates as $date => $tasks) {
                foreach ($tasks as $task) {
                    $amount = $task['hours'] * $task['cost'];
                }
            }
        }

        /** @see https://developer.zipbooks.com/#api-Expenses-PostExpense */
        $this->_call('expenses', [
            'amount' => $amount,
            'date' => date('Y-m-d'),
            'customer_id' => $staff['zipbooks_customer_id'],
            'name' => $staff['name'],
            'category' => $this->expenseCategory,
            'note' => Yii::$app->view->render('/site/_purchase_times' . '', ['times' => $times]),
            //'image_filename' => '',
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