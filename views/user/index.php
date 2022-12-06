<?php

use app\models\User;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
$this->params['buttons'] = ['create' => Html::a('<i class="fas fa-plus-circle text-success"></i>Добавить', ['create'], ['class' => 'btn btn-app'])];
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body pb-0">

                    <?php Pjax::begin(); ?>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'options' => ['class' => 'table-responsive'],
                        'tableOptions' => ['class' => 'table table-striped'],
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                                'headerOptions' => ['style' => 'text-align: center !important;'],
                                'contentOptions' => ['style' => 'text-align: center !important;']
                            ],
                            [
                                'attribute'=>'username',
                                'options' => ['width'=>'30%'],
                                'format'=>'raw',
                                'value' => function($model)
                                {
                                    return Html::a($model->username, ['user/view','id'=>$model->id], ['class'=>'no-pjax']);
                                }
                            ],
                            [
                                'attribute'=>'email',
                                'options' => ['width'=>'30%'],
                                'format'=>'email',
                            ],
                            [
                                'attribute' => 'roles',
                                'format' => 'raw',
                                'filter' => User::getRolesDropdown(),
                                'headerOptions' => ['style' => 'text-align: center !important;'],
                                'contentOptions' => ['style' => 'text-align: center !important;'],
                                'options' => ['width'=>'20%'],
                                'value' => function ($model, $key, $index, $column) {
                                    /** @var User $model */
                                    /** @var \yii\grid\DataColumn $column */
                                    $value = $model->{$column->attribute};
                                    switch ($value) {
                                        case $model->roles == 'admin':
                                            $class = 'danger';
                                            break;
                                        case $model->roles == 'author':
                                            $class = 'primary';
                                            break;
                                        default:
                                            $class = 'secondary';
                                    };
                                    $html = Html::tag('span', Html::encode($model->getRolesName()), ['class' => 'badge bg-' . $class]);
                                    return empty($value) ? null : $html;
                                },
                            ],
                            [
                                'filter' => User::getStatusesArray(),
                                'attribute' => 'status',
                                'options' => ['width'=>'20%'],
                                'headerOptions' => ['style' => 'text-align: center !important;'],
                                'contentOptions' => ['style' => 'text-align: center !important;'],
                                'format' => 'raw',
                                'value' => function ($model, $key, $index, $column) {
                                    /** @var User $model */
                                    /** @var \yii\grid\DataColumn $column */
                                    $value = $model->{$column->attribute};
                                    switch ($value) {
                                        case User::STATUS_ACTIVE:
                                            $class = 'success';
                                            break;
                                        case User::STATUS_INACTIVE:
                                            $class = 'danger';
                                            break;
                                        default:
                                            $class = 'default';
                                    };
                                    $html = Html::tag('span', Html::encode($model->getStatusName()), ['class' => 'badge bg-' . $class]);
                                    return empty($value) ? null : $html;
                                },
                            ],
                        ],
                    ]); ?>

                    <?php Pjax::end(); ?>

                </div>
            </div>
        </div>
    </div>
</div>
