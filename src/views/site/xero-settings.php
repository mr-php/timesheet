<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/**
 * @var $this \yii\web\View
 * @var $model \app\models\forms\XeroSettingsForm
 */

$model->load(Yii::$app->request->post());

$this->title = Yii::t('app', 'Xero Settings');
?>

<h1><?php echo $this->title; ?></h1>

<?php $form = ActiveForm::begin(); ?>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->field($model, 'consumerKey'); ?>

<?php echo $form->field($model, 'consumerSecret'); ?>

<?php echo $form->field($model, 'saleAccountId'); ?>

<?php echo $form->field($model, 'purchaseAccountId'); ?>

<?php echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>
