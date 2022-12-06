<?php

use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Employee $model */
/** @var app\models\Position $positions */
/** @var yii\widgets\ActiveForm $form */
?>

<?php $form = ActiveForm::begin(['id' => 'employee']); ?>

<?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'middle_name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'birthdate')->textInput(['type' => 'date']) ?>

<?= $form->field($model, 'position_id')->dropDownList($positions, ['prompt' => 'Выберите должность']); ?>

<?= $form->field($model, 'status')->dropDownList( $model->getStatusesArray(), ['prompt' => 'Выберите статус']); ?>

<?php ActiveForm::end(); ?>

