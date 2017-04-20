<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;

/**
 * Class SaasuSettingsForm
 * @package app\models\forms
 */
class SaasuSettingsForm extends Model
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
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wsAccessKey', 'fileUid', 'layout', 'saleAccountUid', 'purchaseAccountUid', 'inventoryItemUid',
                'fromEmail', 'saleEmailSubject', 'saleEmailBody', 'purchaseEmailSubject', 'purchaseEmailBody'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'wsAccessKey' => Yii::t('app', 'Access Key'),
            'fileUid' => Yii::t('app', 'File UID'),
            'layout' => Yii::t('app', 'Layout'),
            'saleAccountUid' => Yii::t('app', 'Sale Account IID'),
            'purchaseAccountUid' => Yii::t('app', 'Purchase Account IID'),
            'inventoryItemUid' => Yii::t('app', 'Inventory Item UID'),
            'fromEmail' => Yii::t('app', 'From Email'),
            'saleEmailSubject' => Yii::t('app', 'Sale Email Subject'),
            'saleEmailBody' => Yii::t('app', 'Sale Email Body'),
            'purchaseEmailSubject' => Yii::t('app', 'Purchase Email Subject'),
            'purchaseEmailBody' => Yii::t('app', 'Purchase Email Body'),
        ];
    }
}
