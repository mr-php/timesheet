<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;

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
    public $taxAccountUid;

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
    public function rules(): array
    {
        return [
            [['wsAccessKey', 'fileUid', 'layout', 'taxAccountUid', 'inventoryItemUid',
                'fromEmail', 'emailSubject', 'emailBody'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'wsAccessKey' => Yii::t('app', 'Access Key'),
            'fileUid' => Yii::t('app', 'File UID'),
            'layout' => Yii::t('app', 'Layout'),
            'taxAccountUid' => Yii::t('app', 'Tax Account IID'),
            'inventoryItemUid' => Yii::t('app', 'Inventory Item UID'),
            'fromEmail' => Yii::t('app', 'From Email'),
            'emailSubject' => Yii::t('app', 'Email Subject'),
            'emailBody' => Yii::t('app', 'Email Body'),
        ];
    }
}
