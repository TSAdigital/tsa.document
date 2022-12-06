<?php

use app\models\Employee;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\EmployeeSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Сотрудники';
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
                                'attribute'=>'last_name',
                                'options' => ['width'=>'20%'],
                                'format'=>'raw',
                                'value' => function($model)
                                {
                                    return Html::a($model->last_name, ['employee/view','id'=>$model->id], ['class'=>'no-pjax']);
                                }
                            ],
                            [
                                'attribute'=>'first_name',
                                'options' => ['width'=>'20%'],
                                'format'=>'raw',
                                'value' => function($model)
                                {
                                    return Html::a($model->first_name, ['employee/view','id'=>$model->id], ['class'=>'no-pjax']);
                                }
                            ],
                            [
                                'attribute'=>'middle_name',
                                'options' => ['width'=>'20%'],
                                'format'=>'raw',
                                'value' => function($model)
                                {
                                    return Html::a($model->middle_name, ['employee/view','id'=>$model->id], ['class'=>'no-pjax']);
                                }
                            ],
                            [
                                'attribute'=>'position_name',
                                'options' => ['width'=>'25%'],
                            ],

                            [
                                'filter' => Employee::getStatusesArray(),
                                'attribute' => 'status',
                                'options' => ['width'=>'15%'],
                                'headerOptions' => ['style' => 'text-align: center !important;'],
                                'contentOptions' => ['style' => 'text-align: center !important;'],
                                'format' => 'raw',
                                'value' => function ($model, $key, $index, $column) {
                                    /** @var Employee $model */
                                    /** @var \yii\grid\DataColumn $column */
                                    $value = $model->{$column->attribute};
                                    switch ($value) {
                                        case Employee::STATUS_ACTIVE:
                                            $class = 'success';
                                            break;
                                        case Employee::STATUS_INACTIVE:
                                            $class = 'danger';
                                            break;
                                        default:
                                            $class = 'default';
                                    };
                                    $html = Html::tag('span', Html::encode($model->getStatusName()), ['class' => 'badge badge-' . $class]);
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

