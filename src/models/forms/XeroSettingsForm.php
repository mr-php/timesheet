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
    public $token;

    /**
     * @var string
     */
    public $tenantId;

    /**
     * @var int
     */
    public $saleAccountId;

    /**
     * @var int
     */
    public $purchaseAccountId;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['consumerKey', 'consumerSecret', 'token', 'tenantId', 'saleAccountId', 'purchaseAccountId'], 'required'],
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
            'token' => Yii::t('app', 'Token'),
            'tenantId' => Yii::t('app', 'Tenant ID'),
            'saleAccountId' => Yii::t('app', 'Sale Account ID'),
            'purchaseAccountId' => Yii::t('app', 'Purchase Account ID'),
        ];
    }
}
