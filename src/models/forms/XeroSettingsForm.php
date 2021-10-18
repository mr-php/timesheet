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
    public $accessToken;

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
            [['consumerKey', 'consumerSecret', 'saleAccountId', 'purchaseAccountId'], 'required'],
            [['accessToken', 'tenantId'], 'safe'],
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
            'accessToken' => Yii::t('app', 'Access Token'),
            'tenantId' => Yii::t('app', 'Tenant ID'),
            'saleAccountId' => Yii::t('app', 'Sale Account ID'),
            'purchaseAccountId' => Yii::t('app', 'Purchase Account ID'),
        ];
    }
}
