<?php

use app\models\Position;

use yii\grid\GridView;
use yii\helpers\Html;

use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\PositionSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Должности';
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
                                'attribute'=>'name',
                                'options' => ['width'=>'80%'],
                                'format'=>'raw',
                                'value' => function($model)
                                {
                                    return Html::a($model->name, ['position/view','id'=>$model->id], ['class'=>'no-pjax']);
                                }
                            ],
                            [
                                'filter' => Position::getStatusesArray(),
                                'attribute' => 'status',
                                'options' => ['width'=>'20%'],

                                'headerOptions' => ['style' => 'text-align: center !important;'],
                                'contentOptions' => ['style' => 'text-align: center !important;'],
                                'format' => 'raw',
                                'value' => function ($model, $key, $index, $column) {
                                    /** @var Position $model */
                                    /** @var \yii\grid\DataColumn $column */
                                    $value = $model->{$column->attribute};
                                    switch ($value) {
                                        case Position::STATUS_ACTIVE:
                                            $class = 'success';
                                            break;
                                        case Position::STATUS_INACTIVE:
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
