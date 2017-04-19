<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $model \app\models\forms\SaasuSettingsForm */
/* @var $this \yii\web\View */

$this->title = Yii::t('app', 'Manage Application Settings');
?>
<?php $form = ActiveForm::begin(); ?>

<?php echo $form->field($model, 'saasuWsAccessKey'); ?>

<?php echo $form->field($model, 'saasuFileUid'); ?>

<?php echo $form->field($model, 'saasuLayout'); ?>

<?php echo $form->field($model, 'saasuTaxAccountUid'); ?>

<?php echo $form->field($model, 'saasuInventoryItemUid'); ?>

<?php echo $form->field($model, 'saasuFromEmail'); ?>

<?php echo $form->field($model, 'saasuEmailSubject'); ?>

<?php echo $form->field($model, 'saasuEmailBody')->textarea(); ?>

<?php echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>
