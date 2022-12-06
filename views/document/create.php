<?php

/** @var yii\web\View $this */
/** @var app\models\Document $model */
/** @var app\models\User $users */

use yii\bootstrap4\Html;

$this->title = 'Новый документ';
$this->params['breadcrumbs'][] = ['label' => 'Документы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['buttons'] = [
    'save' => Html::submitButton('<i class="far fa-save text-green"></i>Сохранить', ['class' => 'btn btn-app', 'form'=> 'document']),
    'undo' => Html::a('<i class="far fa-arrow-alt-circle-left text-muted"></i>Вернуться', ['document/index'], ['class' => 'btn btn-app'])
];
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <?= $this->render('_form', [
                        'model' => $model,
                        'users' => $users,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>
