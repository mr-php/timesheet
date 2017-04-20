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
            'name' => 'Admin',
            'toggl_api_key' => '817212b50437a8531fdac89bc22e7dc8',
            'rate' => 100,
            'cost' => 50,
            'multiplier' => 1,
            'tax_rate' => 0.1,
            'projects' => [
                'none' => [
                    'rate' => 100,
                    'multiplier' => 1,
                ],
            ],
        ],
    ], JSON_PRETTY_PRINT))); ?>

<?php echo $form->field($model, 'projects')->textarea()->hint(Yii::t('app', 'Example:') . Html::tag('pre', \yii\helpers\Json::encode([
        'none' => [
            'name' => 'No Project',
            'email' => 'test@mailinator.com',
            'saasu_contact_uid' => 221813,
            'saasu_tax_code' => 'G1', // 'G1,G2',
            'tax_rate' => 0.1,
            'base_rate' => 150,
            'base_hours' => 2,
            'cap_hours' => 4,
        ],
    ], JSON_PRETTY_PRINT))); ?>

<?php echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>
