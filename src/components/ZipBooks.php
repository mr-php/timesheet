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
     * @throws Exception
     */
    public function createInvoice($pid, $times)
    {
        // todo
    }

    /**
     * @param string $sid
     * @param array $times
     * @throws Exception
     */
    public function createExpense($sid, $times)
    {
        // todo
    }

}