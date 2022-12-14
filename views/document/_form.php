<?php

use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use kartik\select2\Select2;


/** @var yii\web\View $this */
/** @var app\models\Document $model */
/** @var app\models\User $users */
/** @var yii\widgets\ActiveForm $form */
?>

<?php $form = ActiveForm::begin(['id' => 'document']); ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'date')->widget(DatePicker::classname(), [
    'options' => ['placeholder' => 'Ввод даты ...'],
    'value' => 'dd.mm.yyyy',
    'pluginOptions' => [
        'format' => 'dd.mm.yyyy',
        'autoclose' => true,
        'todayBtn' => true,
        'todayHighlight' => true,
    ]
]) ?>

<?= $form->field($model, 'description')->textarea(['rows' => 5]) ?>

<?= $form->field($model, 'resolution')->widget(Select2::classname(), [
    'data' => $users,
    'theme' => 'krajee-bs3',
    'size' => 'lg',
    'options' => ['placeholder' => 'Выберите сотрудника...', 'multiple' => true],
]) ?>

<?= $form->field($model, 'status')->dropDownList( $model->getStatusesArray(), ['prompt' => 'Выберите статус']) ?>

<?= $model->isNewRecord ? $form->field($model, 'send_email', ['enableClientValidation' => false])->checkbox(['custom' => true, 'switch' => true])->label('Оповестить участников на электронную почту'): null; ?>


<?php ActiveForm::end(); ?>


