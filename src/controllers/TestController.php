<?php

namespace app\controllers;

use AJT\Toggl\TogglClient;
use Yii;
use yii\web\Controller;

/**
 * Site controller.
 */
class TestController extends Controller
{
    public function actionIndex()
    {
        $times = Yii::$app->timeSheet->getTimes();
        $times = Yii::$app->timeSheet->getTotals($times);
        print_r($times);
    }

    public function actionToggl()
    {
        $toggl = TogglClient::factory([
            'api_key' => '817212b50437a8531fdac89bc22e7dc8',
            'apiVersion' => 'v8'
        ]);
        $workspaces = $toggl->getWorkspaces();
        print 'Workspaces' . "\n";
        print_r($workspaces);
        foreach ($workspaces as $workspace) {
            print 'Projects' . "\n";
            $projects = $toggl->getProjects(['id' => $workspace['id']]);
            print_r($projects);
        }

        $clients = $toggl->getClients();
        $timeEntries = $toggl->getTimeEntries([
            //'start_date' => date('c', strtotime('00:00 -1week')),
            //'end_date' => date('c', strtotime('00:00 -0week')),
        ]);
        print 'Clients' . "\n";
        print_r($clients);
        print 'Time Entries' . "\n";
        print_r($timeEntries);
    }

    public function actionSaasu()
    {
        $item1 = new ServiceInvoiceItem();
        $item1->description = "test service 1";
        $item1->accountUid = $account->uid;
        $item1->taxCode = TaxCode::SaleInclGst;
        $item1->totalAmountInclTax = 11.12;

        $i = new Invoice();
        $i->invoiceType = InvoiceTypeAU::TaxInvoice;
        $i->transactionType = TransactionType::Sale;
        $i->date = DateTime::getDate(time());
        $i->summary = "This is a test summary";
        $i->notes = "Test";
        $i->tags = "test";
        $i->requiresFollowUp = 'false';
        $i->dueOrExpiryDate = DateTime::getDate(time() + 86400 * 14);
        $i->layout = InvoiceLayout::Service;
        $i->status = InvoiceStatus::Invoice;
        //$i->invoiceNumber = "<Auto Number>";
        $tradingTerms = new TradingTerms();
        $tradingTerms->type = TradingTermsType::DueIn;
        $tradingTerms->intervalType = IntervalType::Day;
        $tradingTerms->interval = 7;
        $i->tradingTerms = $tradingTerms;
        $i->isSent = 'false';
        $i->invoiceItems = [$item1];

        //return $this->render('index');
    }
}
