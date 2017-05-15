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
    public function rules()
    {
        return [
            [['fromEmail', 'logoFilename', 'invoiceTerms', 'invoiceNotes', 'invoiceEmailSubject', 'invoiceEmailBody', 'expenseCategory', 'expenseEmailSubject', 'expenseEmailBody'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fromEmail' => Yii::t('app', 'From Email'),
            'logoFilename' => Yii::t('app', 'Logo Filename'),
            'invoiceTerms' => Yii::t('app', 'Invoice Terms'),
            'invoiceNotes' => Yii::t('app', 'Invoice Notes'),
            'saleEmailSubject' => Yii::t('app', 'Invoice Email Subject'),
            'saleEmailBody' => Yii::t('app', 'Invoice Email Body'),
            'expenseCategory' => Yii::t('app', 'Expense Category'),
            'expenseEmailSubject' => Yii::t('app', 'Expense Email Subject'),
            'expenseEmailBody' => Yii::t('app', 'Expense Email Body'),
        ];
    }
}
