<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use yii\helpers\Html;

/**
 * Class ZipBooksSettingsForm
 * @package app\models\forms
 */
class ZipBooksSettingsForm extends Model
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
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password', 'logoFilename', 'invoiceTerms', 'invoiceNotes', 'invoiceEmailSubject', 'invoiceEmailBody', 'expenseCategory'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
            'logoFilename' => Yii::t('app', 'Logo Filename'),
            'invoiceTerms' => Yii::t('app', 'Invoice Terms'),
            'invoiceNotes' => Yii::t('app', 'Invoice Notes'),
            'saleEmailSubject' => Yii::t('app', 'Invoice Email Subject'),
            'saleEmailBody' => Yii::t('app', 'Invoice Email Body'),
            'expenseCategory' => Yii::t('app', 'Expense Category'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'email' => Yii::t('app', 'ZipBooks login email.'),
            'password' => Yii::t('app', 'ZipBooks login password.'),
            'expenseCategory' => Html::a(Yii::t('app', 'View categories'), 'https://app.zipbooks.com/categories', ['target' => '_blank']),
            'invoiceEmailSubject' => Yii::t('app', 'Vars:') . ' <code>{number}</code>, <code>{invoice}</code>.',
            'invoiceEmailBody' => Yii::t('app', 'Vars:') . ' <code>{number}</code>, <code>{invoice}</code>, <code>{times}</code>.',
        ];
    }

}
