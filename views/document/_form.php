<?php

use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Document $model */
/** @var app\models\User $users */
/** @var yii\widgets\ActiveForm $form */
?>

<?php $form = ActiveForm::begin(['id' => 'document']); ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'description')->textarea() ?>

<?= $form->field($model, 'date')->textInput(['type' => 'date']) ?>

<?= $form->field($model, 'resolution')->dropDownList($users,
 [
    'multiple'=>'multiple',

 ]
);
?>

<?= $form->field($model, 'status')->dropDownList( $model->getStatusesArray(), ['prompt' => 'Выберите статус']); ?>

<?= $model->isNewRecord ? $form->field($model, 'send_email')->checkbox()->label('Оповестить участников на электронную почту') : null; ?>

<?php ActiveForm::end(); ?>


