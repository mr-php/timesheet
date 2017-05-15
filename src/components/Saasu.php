<?php

namespace app\components;

use Dlin\Saasu\Entity\EmailMessage;
use Dlin\Saasu\Entity\Invoice;
use Dlin\Saasu\Entity\InvoiceInstruction;
use Dlin\Saasu\Entity\ItemInvoiceItem;
use Dlin\Saasu\Entity\ServiceInvoiceItem;
use Dlin\Saasu\Enum\InvoiceLayout;
use Dlin\Saasu\Enum\InvoiceStatus;
use Dlin\Saasu\Enum\InvoiceTypeAU;
use Dlin\Saasu\Enum\TransactionType;
use Dlin\Saasu\SaasuAPI;
use Dlin\Saasu\Util\DateTime;
use Yii;
use yii\base\Component;
use yii\base\Exception;

/**
 * Class Saasu
 * @package app\components
 *
 * @property SaasuAPI $api
 */
class Saasu extends Component
{

    /**
     * @var string
     */
    public $wsAccessKey;

    /**
     * @var int
     */
    public $fileUid;

    /**
     * @var string
     */
    public $layout = 'S';

    /**
     * @var int
     */
    public $saleAccountUid;

    /**
     * @var int
     */
    public $purchaseAccountUid;

    /**
     * @var int
     */
    public $inventoryItemUid;

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
     * @var SaasuAPI
     */
    private $_api;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $settings = ['wsAccessKey', 'fileUid', 'layout', 'saleAccountUid', 'purchaseAccountUid', 'inventoryItemUid',
            'fromEmail', 'saleEmailSubject', 'saleEmailBody', 'purchaseEmailSubject', 'purchaseEmailBody'];
        foreach ($settings as $key) {
            $value = Yii::$app->settings->get('SaasuSettingsForm', $key);
            if ($value) {
                $this->$key = $value;
            }
        }
    }

    /**
     * @return SaasuAPI
     */
    public function getApi()
    {
        if (!$this->_api) {
            $this->_api = new SaasuAPI($this->wsAccessKey, $this->fileUid);
        }
        return $this->_api;
    }

    /**
     * @param string $pid
     * @param array $times
     * @throws Exception
     */
    public function createSaleInvoice($pid, $times)
    {
        $project = Yii::$app->timeSheet->projects[$pid];

        // create an instruction
        $instruction = new InvoiceInstruction();
        $instruction->emailMessage = new EmailMessage();
        $instruction->emailToContact = 'true';
        $instruction->emailMessage->to = $project['email'];
        $instruction->emailMessage->bcc = $this->fromEmail;
        $instruction->emailMessage->from = $this->fromEmail;
        $instruction->emailMessage->subject = strtr($this->saleEmailSubject, ['{project}' => $project['name']]);
        $instruction->emailMessage->body = strtr($this->saleEmailBody, [
            '{project}' => $project['name'],
            '{times}' => Yii::$app->view->render('/site/_sale-times' . '', ['times' => $times]),
        ]);

        // create an invoice
        $invoice = new Invoice();
        $invoice->contactUid = $project['saasu_contact_uid'];
        $invoice->invoiceType = InvoiceTypeAU::TaxInvoice;
        $invoice->transactionType = TransactionType::Sale;
        $invoice->status = InvoiceStatus::Invoice;
        $invoice->layout = $this->layout;
        $invoice->invoiceNumber = "<Auto Number>";
        $invoice->date = DateTime::getDate(time());
        $invoice->dueOrExpiryDate = DateTime::getDate(time() + 86400 * 7);
        $invoice->summary = "Development for {$project['name']}";
        $invoice->tags = ['timesheet'];
        $invoice->invoiceItems = [];
        foreach ($times as $sid => $dates) {
            foreach ($dates as $date => $tasks) {
                foreach ($tasks as $task) {
                    if ($this->layout == InvoiceLayout::Service) {
                        $item = new ServiceInvoiceItem();
                        $item->accountUid = $this->saleAccountUid;
                        $item->totalAmountInclTax = round($task['hours'] * $task['sell'], 2);
                    } elseif ($this->layout == InvoiceLayout::Item) {
                        $item = new ItemInvoiceItem();
                        $item->inventoryItemUid = $this->inventoryItemUid;
                        $item->quantity = round($task['hours'], 2);
                        $item->unitPriceInclTax = round($task['sell'], 2);
                    } else {
                        throw new Exception('invalid layout');
                    }
                    $item->description = date('Y-m-d', strtotime($task['date'])) . ' ' . Yii::$app->timeSheet->staff[$task['sid']]['name'] . ' ' . Helper::formatHours($task['hours']) . ' - ' . $task['description'];
                    $item->taxCode = $project['saasu_tax_code'];
                    $invoice->invoiceItems[] = $item;
                }
            }
        }

        // save entities
        $this->api->saveEntity($invoice, $instruction);
    }

    /**
     * @param string $sid
     * @param array $times
     * @throws Exception
     */
    public function createPurchaseInvoice($sid, $times)
    {
        $staff = Yii::$app->timeSheet->staff[$sid];
        if (!isset($staff['saasu_contact_uid'])) {
            return;
        }

        // create an instruction
        $instruction = new InvoiceInstruction();
        $instruction->emailMessage = new EmailMessage();
        $instruction->emailToContact = 'true';
        $instruction->emailMessage->to = isset($staff['email']) ? $staff['email'] : $this->fromEmail;
        $instruction->emailMessage->bcc = $this->fromEmail;
        $instruction->emailMessage->from = $this->fromEmail;
        $instruction->emailMessage->subject = strtr($this->purchaseEmailSubject, ['{staff}' => $staff['name']]);
        $instruction->emailMessage->body = strtr($this->purchaseEmailBody, [
            '{staff}' => $staff['name'],
            '{times}' => Yii::$app->view->render('/site/_purchase-times' . '', ['times' => $times]),
        ]);

        // create an invoice
        $invoice = new Invoice();
        $invoice->contactUid = $staff['saasu_contact_uid'];
        $invoice->invoiceType = InvoiceTypeAU::TaxInvoice;
        $invoice->transactionType = TransactionType::Purchase;
        $invoice->status = InvoiceStatus::Invoice;
        $invoice->layout = $this->layout;
        //$invoice->invoiceNumber = "<Auto Number>";
        $invoice->date = DateTime::getDate(time());
        $invoice->dueOrExpiryDate = DateTime::getDate(time() + 86400 * 7);
        $invoice->summary = "Development by {$staff['name']}";
        $invoice->tags = ['timesheet'];
        $invoice->invoiceItems = [];
        foreach ($times as $sid => $dates) {
            foreach ($dates as $date => $tasks) {
                foreach ($tasks as $task) {
                    if ($this->layout == InvoiceLayout::Service) {
                        $item = new ServiceInvoiceItem();
                        $item->accountUid = $this->purchaseAccountUid;
                        $item->totalAmountInclTax = round($task['hours'] * $task['cost'], 2);
                    } elseif ($this->layout == InvoiceLayout::Item) {
                        $item = new ItemInvoiceItem();
                        $item->inventoryItemUid = $this->inventoryItemUid;
                        $item->quantity = round($task['hours'], 2);
                        $item->unitPriceInclTax = round($task['cost'], 2);
                    } else {
                        throw new Exception('invalid layout');
                    }
                    $item->description = date('Y-m-d', strtotime($task['date'])) . ' ' . Yii::$app->timeSheet->projects[$task['pid']]['name'] . ' ' . Helper::formatHours($task['hours']) . ' - ' . $task['description'];
                    $item->taxCode = $staff['saasu_tax_code'];
                    $invoice->invoiceItems[] = $item;
                }
            }
        }

        // save entities
        $this->api->saveEntity($invoice, $instruction);
    }

}