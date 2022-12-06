<?php

use yii\bootstrap4\Html; 

?>

<div class="d-flex flex-start align-items-center mt-2">
    <div>
        <h6 class="fw-bold text-primary mb-1"><?= $model->author0->employee_name ?></h6>
        <p class="text-muted small mb-0">
            Дата и время - <?= Yii::$app->formatter->asDatetime($model->created_at) ?>
        </p>
    </div>
</div>

<p class="mt-1 mb-0"><?= $model->text ?></p>

<div class="small d-flex justify-content-start mb-4">

    <?= \Yii::$app->user->can('admin') ? Html::a('Удалить', ['delete-discussion', 'id' => $id, 'discussion' => $model->id], [
        'class' => 'd-flex align-items-center me-3',
        'data' => [
            'confirm' => 'Вы уверены, что хотите удалить этот комментарий?',
            'method' => 'post',
        ],
    ]) : null ?>

</div>
