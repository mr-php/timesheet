<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;

/**
 * Class ZipBooksSettingsForm
 * @package app\models\forms
 */
class ZipBooksSettingsForm extends Model
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
    public function rules()
    {
        return [
            [['fromEmail', 'invoiceEmailSubject', 'invoiceEmailBody', 'expenseEmailSubject', 'expenseEmailBody'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fromEmail' => Yii::t('app', 'From Email'),
            'saleEmailSubject' => Yii::t('app', 'Invoice Email Subject'),
            'saleEmailBody' => Yii::t('app', 'Invoice Email Body'),
            'expenseEmailSubject' => Yii::t('app', 'Expense Email Subject'),
            'expenseEmailBody' => Yii::t('app', 'Expense Email Body'),
        ];
    }
}
