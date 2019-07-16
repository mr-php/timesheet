<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use yii\helpers\Html;

/**
 * Class TimeSheetSettingsForm
 * @package app\models\forms
 */
class TimeSheetSettingsForm extends Model
{
    /**
     * @var string
     */
    public $staff;

    /**
     * @var string
     */
    public $projects;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['staff', 'projects'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'staff' => Yii::t('app', 'Staff'),
            'projects' => Yii::t('app', 'Projects'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'staff' => Yii::t('app', 'Example:') . Html::tag('pre', \yii\helpers\Json::encode([
                    'admin' => [
                        'name' => 'Test',
                        'email' => 'timesheet-staff@mailinator.com',
                        'toggl_api_key' => 'a67aaf4b789d150863f5f2b6583fb4ff',
                        'xero_contact_uid' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
                        'xero_tax_code' => 'BASEXCLUDED', // OUTPUT|BASEXCLUDED
                        'xero_sale_account_id' => '200',
                        'xero_purchase_account_id' => '310',
                        'xero_item_code' => 'MY-ITEM',
                        //'zipbooks_contact_id' => 1234567890,
                        //'saasu_contact_uid' => 1234567890,
                        //'saasu_tax_code' => 'G11,G14', // G11 = inc gst
                        //'saasu_sale_account_id' => 1234567890,
                        //'saasu_purchase_account_id' => 1234567890,
                        'sell' => 100,
                        'cost' => 50,
                        'multiplier' => 1,
                        'tax_rate' => 0.1,
                        'projects' => [
                            'none' => [
                                'sell' => 90,
                                'cost' => 60,
                                'multiplier' => 0.9,
                            ],
                        ],
                    ],
                ], JSON_PRETTY_PRINT)),
            'projects' => Yii::t('app', 'Example:') . Html::tag('pre', \yii\helpers\Json::encode([
                    'none' => [
                        'name' => 'No Project',
                        'email' => 'timesheet-project@mailinator.com',
                        'xero_contact_id' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
                        'xero_tax_code' => 'OUTPUT', // OUTPUT|BASEXCLUDED
                        //'zipbooks_contact_id' => 1234567890,
                        //'saasu_contact_uid' => 1234567890,
                        //'saasu_tax_code' => 'G1,G2', // G1 = inc gst
                        'tax_rate' => 0.1,
                        'base_rate' => 150,
                        'base_hours' => 2,
                        'cap_hours' => 4,
                    ],
                ], JSON_PRETTY_PRINT)),
        ];
    }

}
