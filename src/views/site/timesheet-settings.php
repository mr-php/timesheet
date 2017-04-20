<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $model \app\models\forms\TimeSheetSettingsForm */
/* @var $this \yii\web\View */

$this->title = Yii::t('app', 'TimeSheet Settings');
?>
<?php $form = ActiveForm::begin(); ?>

<?php echo $form->field($model, 'staff')->textarea()->hint(Yii::t('app', 'Example:') . Html::tag('pre', \yii\helpers\Json::encode([
        'admin' => [
            'name' => 'Test',
            'email' => 'timesheet-staff@mailinator.com',
            'toggl_api_key' => 'a67aaf4b789d150863f5f2b6583fb4ff',
            'saasu_contact_uid' => 1234567890,
            'saasu_tax_code' => 'G11,G14', // G11 = inc gst
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
    ], JSON_PRETTY_PRINT))); ?>

<?php echo $form->field($model, 'projects')->textarea()->hint(Yii::t('app', 'Example:') . Html::tag('pre', \yii\helpers\Json::encode([
        'none' => [
            'name' => 'No Project',
            'email' => 'timesheet-project@mailinator.com',
            'saasu_contact_uid' => 1234567890,
            'saasu_tax_code' => 'G1,G2', // G1 = inc gst
            'tax_rate' => 0.1,
            'base_rate' => 150,
            'base_hours' => 2,
            'cap_hours' => 4,
        ],
    ], JSON_PRETTY_PRINT))); ?>

<?php echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>
