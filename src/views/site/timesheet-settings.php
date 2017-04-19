<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $model \app\models\forms\TimeSheetSettingsForm */
/* @var $this \yii\web\View */

$this->title = Yii::t('app', 'TimeSheet Settings');
?>
<?php $form = ActiveForm::begin(); ?>

<?php echo $form->field($model, 'staff')->textarea(); ?>

<?php echo $form->field($model, 'projects')->textarea(); ?>

<?php echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>