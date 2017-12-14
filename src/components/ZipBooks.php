<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\helpers\Inflector;
use yii\helpers\Json;

/**
 * Class ZipBooks
 * @package app\components
 */
class ZipBooks extends Component
{

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $password;

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
     * @var
     */
    private $_token;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $settings = ['email', 'password', 'logoFilename', 'invoiceTerms', 'invoiceNotes', 'invoiceEmailSubject', 'invoiceEmailBody', 'expenseCategory', 'expenseEmailSubject', 'expenseEmailBody'];
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
        $number = date('Ymd') . '-' . $pid;

        /** @see https://developer.zipbooks.com/#api-Invoices-PostInvoice */
        $invoice = [
            'customer' => $project['zipbooks_contact_id'],
            'number' => $number,
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
                        'name' => date('Y-m-d', strtotime($task['date'])) . ' ' . Yii::$app->timeSheet->staff[$task['sid']]['name'] . ' ' . Helper::formatHours($task['hours']),
                        'notes' => $task['description'],
                        'rate' => round($task['sell'], 2),
                        'quantity' => round($task['hours'], 2),
                    ];
                }
            }
        }
        $response = $this->_request('invoices', $invoice);

        /** @see https://developer.zipbooks.com/#api-Invoices-postInvoiceSend */
        $this->_request('invoices/' . $response['id'] . '/send', [
            'send_to' => $project['email'],
            'subject' => strtr($this->invoiceEmailSubject, ['{project}' => $project['name'], '{number}' => $number]),
            'message' => strtr($this->invoiceEmailBody, [
                '{project}' => $project['name'],
                '{number}' => $number,
                '{times}' => Yii::$app->view->render('/site/_sale-times' . '', ['times' => $times]),
            ]),
            'bcc' => true,
            'pdf' => true,
        ]);
    }

    /**
     * @param string $sid
     * @param array $times
     */
    public function createExpense($sid, $times)
    {
        return;
        $staff = Yii::$app->timeSheet->staff[$sid];

        $journal = [
            'date' => date('Y-m-d'),
            'name' => $staff['name'],
            'note' => Yii::$app->view->render('/site/_purchase-times' . '', ['times' => $times]),
            'journal_entry_lines' => [],
        ];
        $i = 0;
        foreach ($times as $sid => $dates) {
            foreach ($dates as $date => $tasks) {
                foreach ($tasks as $task) {
                    $amount = round($task['hours'] * $task['cost'], 4);
                    $name = date('Y-m-d', strtotime($task['date'])) . ' ' . Yii::$app->timeSheet->projects[$task['pid']]['name'] . ' ' . Helper::formatHours($task['hours']) . ' - ' . $task['description'];
                    $i++;
                    $journal['journal_entry_lines'][$i] = [
                        'amount' => $amount,
                        'kind' => 'debit',
                        'name' => $name,
                        'chart_account_id' => $this->expenseCategory,
                        'customer_id' => $staff['zipbooks_contact_id'],
                        //'invoice_id' => '',
                    ];
                    $i++;
                    $journal['journal_entry_lines'][$i] = [
                        'amount' => $amount,
                        'kind' => 'credit',
                        'name' => $name,
                        'chart_account_id' => 4834140,
                        'customer_id' => $staff['zipbooks_contact_id'],
                        //'invoice_id' => '',
                    ];
                }
            }
        }

        $this->_request('journal_entries', $journal);
    }

    /**
     * @param $action
     * @param array $params
     * @return mixed
     */
    private function _request($action, $params = [])
    {
        $client = new \GuzzleHttp\Client();
        return Json::decode($client->request('POST', 'https://api.zipbooks.com/v1/' . $action, [
            'json' => $params,
            'headers' => ['Authorization' => 'Bearer ' . $this->_token()],
        ])->getBody());
    }

    /**
     * @return mixed
     */
    private function _token()
    {
        $client = new \GuzzleHttp\Client();
        if (!$this->_token) {
            /** @see https://developer.zipbooks.com/#api-Authentication-PostAuthLogin */
            $response = Json::decode($client->request('POST', 'https://api.zipbooks.com/v1/auth/login', [
                'json' => ['email' => $this->email, 'password' => $this->password],
            ])->getBody());
            $this->_token = $response['token'];
        }
        return $this->_token;
    }

}