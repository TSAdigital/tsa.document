<?php

use yii\bootstrap4\ActiveForm;
use yii\widgets\MaskedInput;

/** @var yii\web\View $this */
/** @var app\models\User $model */
/** @var app\models\Employee $employees */
/** @var yii\widgets\ActiveForm $form */
?>


<?php $form = ActiveForm::begin(['id' => 'user']); ?>

<?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'new_password')->passwordInput() ?>

<?= $form->field($model, 'employee_id')->dropDownList($employees, ['prompt' => 'Выберите сотрудника']); ?>

<?= $form->field($model, 'roles')->dropDownList($model->getRolesDropdown(), ['prompt' => 'Выберите роль']); ?>

<?= $form->field($model, 'email')->widget(MaskedInput::className(),[
    'clientOptions' => [
        'alias' =>  'email'
    ],
]);
?>

<?= $form->field($model, 'status')->dropDownList($model->getStatusesArray(), ['prompt' => 'Выберите статус']); ?>

<?php ActiveForm::end(); ?>


