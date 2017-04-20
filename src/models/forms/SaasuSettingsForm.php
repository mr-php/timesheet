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
    public $emailSubject;

    /**
     * @var string
     */
    public $emailBody;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wsAccessKey', 'fileUid', 'layout', 'saleAccountUid', 'purchaseAccountUid', 'inventoryItemUid',
                'fromEmail', 'emailSubject', 'emailBody'], 'required'],
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
            'emailSubject' => Yii::t('app', 'Email Subject'),
            'emailBody' => Yii::t('app', 'Email Body'),
        ];
    }
}
