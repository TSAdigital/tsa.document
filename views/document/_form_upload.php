<?php

use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\UploadForm $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php $form = ActiveForm::begin(['id' => 'upload', 'options' => ['enctype' => 'multipart/form-data']]); ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'file')->fileInput() ?>

<?php ActiveForm::end(); ?>

