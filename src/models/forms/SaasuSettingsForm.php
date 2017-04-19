<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;

class SaasuSettingsForm extends Model
{

    /**
     * @var string
     */
    public $saasuWsAccessKey;

    /**
     * @var int
     */
    public $saasuFileUid;

    /**
     * @var string
     */
    public $saasuLayout = 'S';

    /**
     * @var int
     */
    public $saasuTaxAccountUid;

    /**
     * @var int
     */
    public $saasuInventoryItemUid;

    /**
     * @var string
     */
    public $saasuFromEmail;

    /**
     * @var string
     */
    public $saasuEmailSubject;

    /**
     * @var string
     */
    public $saasuEmailBody;

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['saasuWsAccessKey', 'saasuFileUid', 'saasuLayout', 'saasuTaxAccountUid', 'saasuInventoryItemUid',
                'saasuFromEmail', 'saasuEmailSubject', 'saasuEmailBody'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'saasuWsAccessKey' => Yii::t('app', 'Access Key'),
            'saasuFileUid' => Yii::t('app', 'File UID'),
            'saasuLayout' => Yii::t('app', 'Layout'),
            'saasuTaxAccountUid' => Yii::t('app', 'Tax Account IID'),
            'saasuInventoryItemUid' => Yii::t('app', 'Inventory Item UID'),
            'saasuFromEmail' => Yii::t('app', 'From Email'),
            'saasuEmailSubject' => Yii::t('app', 'Email Subject'),
            'saasuEmailBody' => Yii::t('app', 'Email Body'),
        ];
    }
}
