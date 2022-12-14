<?php

use yii\bootstrap4\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Position $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Должности', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['buttons'] = [
    'update' => Html::a('<i class="fas fa-edit text-primary"></i> Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-app']),
    'delete' =>  Html::a('<i class="fas fa-trash-alt text-danger"></i> Удалить', ['delete', 'id' => $model->id], [
        'class' => 'btn btn-app',
        'data' => [
            'confirm' => 'Вы уверены, что хотите удалить этого сотрудника?',
            'method' => 'post',
        ],
    ]),
    'undo' => Html::a('<i class="far fa-arrow-alt-circle-left text-muted"></i>Вернуться', ['position/index'], ['class' => 'btn btn-app'])
];
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            [
                                'attribute' => 'name',
                                'captionOptions' => ['width' => '200px'],
                            ],
                            [
                                'attribute' => 'status',
                                'value' => $model->getStatusName(),
                            ],
                            'created_at:datetime',
                            'updated_at:datetime',
                        ],
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>