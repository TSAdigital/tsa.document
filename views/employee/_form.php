<?php

use kartik\date\DatePicker;
use yii\bootstrap4\ActiveForm;
use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var app\models\Employee $model */
/** @var app\models\Position $positions */
/** @var yii\widgets\ActiveForm $form */
?>

<?php $form = ActiveForm::begin(['id' => 'employee']); ?>

<?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'middle_name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'birthdate')->widget(DatePicker::classname(), [
    'options' => ['placeholder' => 'Ввод даты ...'],
    'value' => 'dd.mm.yyyy',
    'pluginOptions' => [
        'format' => 'dd.mm.yyyy',
        'autoclose' => true,
        'todayBtn' => true,
        'todayHighlight' => true,
    ]
]) ?>

<?= $form->field($model, 'position_id')->widget(Select2::classname(),
        [
            'data' => $positions,
            'options' => ['placeholder' => 'Выберите должность...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
?>

<?= $form->field($model, 'status')->dropDownList( $model->getStatusesArray(), ['prompt' => 'Выберите статус']); ?>

<?php ActiveForm::end(); ?>

