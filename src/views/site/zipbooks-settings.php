<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var $this \yii\web\View
 * @var $model \app\models\forms\ZipBooksSettingsForm
 */

$this->title = Yii::t('app', 'ZipBooks Settings');
?>
<?php $form = ActiveForm::begin(); ?>

<?php echo $form->field($model, 'fromEmail'); ?>

<?php echo $form->field($model, 'invoiceEmailSubject'); ?>

<?php echo $form->field($model, 'invoiceEmailBody')->textarea(); ?>

<?php echo $form->field($model, 'expenseEmailSubject'); ?>

<?php echo $form->field($model, 'expenseEmailBody')->textarea(); ?>

<?php echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>
