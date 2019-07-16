<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;

/**
 * Class XeroSettingsForm
 * @package app\models\forms
 */
class XeroSettingsForm extends Model
{

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
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['consumerKey', 'consumerSecret', 'publicKey', 'privateKey', 'saleAccountId', 'purchaseAccountId',
                'fromEmail', 'saleEmailSubject', 'saleEmailBody', 'purchaseEmailSubject', 'purchaseEmailBody'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'consumerKey' => Yii::t('app', 'Consumer Key'),
            'consumerSecret' => Yii::t('app', 'Consumer Secret'),
            'publicKey' => Yii::t('app', 'Public Key'),
            'privateKey' => Yii::t('app', 'Private Key'),
            'saleAccountUid' => Yii::t('app', 'Sale Account ID'),
            'purchaseAccountUid' => Yii::t('app', 'Purchase Account ID'),
            'fromEmail' => Yii::t('app', 'From Email'),
            'saleEmailSubject' => Yii::t('app', 'Sale Email Subject'),
            'saleEmailBody' => Yii::t('app', 'Sale Email Body'),
            'purchaseEmailSubject' => Yii::t('app', 'Purchase Email Subject'),
            'purchaseEmailBody' => Yii::t('app', 'Purchase Email Body'),
        ];
    }
}
