<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/**
 * @var $this \yii\web\View
 * @var $model \app\models\forms\ZipBooksSettingsForm
 */

$model->load(Yii::$app->request->post());

$this->title = Yii::t('app', 'ZipBooks Settings');
?>

<h1><?php echo $this->title; ?></h1>

<?php $form = ActiveForm::begin(); ?>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->field($model, 'email'); ?>

<?php echo $form->field($model, 'password')->passwordInput(); ?>

<?php echo $form->field($model, 'logoFilename'); ?>

<?php echo $form->field($model, 'invoiceTerms'); ?>

<?php echo $form->field($model, 'invoiceNotes'); ?>

<?php echo $form->field($model, 'invoiceEmailSubject'); ?>

<?php echo $form->field($model, 'invoiceEmailBody')->textarea(); ?>

<?php echo $form->field($model, 'expenseCategory'); ?>

<?php //echo $form->field($model, 'expenseEmailSubject'); ?>

<?php //echo $form->field($model, 'expenseEmailBody')->textarea(); ?>

<?php echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>
