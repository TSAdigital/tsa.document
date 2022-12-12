<?php

use app\models\Document;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\StringHelper;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\DocumentSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Документы';
$this->params['breadcrumbs'][] = $this->title;
$this->params['buttons'] = \Yii::$app->user->can('createDocument') ? ['create' => Html::a('<i class="fas fa-plus-circle text-success"></i>Добавить', ['create'], ['class' => 'btn btn-app'])] : null;

$layout = <<< HTML
{input1}
{separator}
{input2}
<div class="input-group-append">
    <span class="input-group-text kv-date-remove">
        <i class="fas fa-times kv-dp-icon"></i>
    </span>
</div>
HTML;
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
                                'contentOptions' => ['style' => 'text-align: center !important; vertical-align: middle !important;']
                            ],
                            [
                                'attribute'=> 'name',
                                'options' => ['width'=>'55%'],
                                'headerOptions' => ['style' => 'min-width:250px'],
                                'contentOptions' => ['style' => 'vertical-align: middle !important;'],
                                'format'=>'raw',
                                'value' => function($model)
                                {
                                    $viewed = ($model->author != Yii::$app->user->identity->getId() and empty($model->isViewed($model->id))) ? ' <sup><span class="badge badge-pill badge-info">НОВЫЙ</span></sup> ' : null;
                                    $name = Html::a(StringHelper::truncate($model->name,80,'...'), ['document/view','id'=>$model->id], ['class'=>'no-pjax']);
                                    $badge = (empty($model->resolution) ? ' <sup><span class="badge badge-pill badge-success">ПУБЛИЧНЫЙ</span></sup>' : ' <sup><span class="badge badge-pill badge-danger">ЧАСТНЫЙ</span></sup>');
                                    return  $name . $viewed . $badge;
                                }
                            ],
                            [
                                'attribute'=> 'document_author',
                                'headerOptions' => ['style' => 'min-width:250px'],
                                'contentOptions' => ['style' => 'vertical-align: middle !important;'],
                                'options' => ['width'=>'20%'],
                                'format'=>'raw',
                                'value' => function($model) {
                                    return Html::a($model->user->employee_name, ['site/profile','id' => $model->user->id], ['class'=>'no-pjax']);
                                }
                            ],
                            [
                                'filter' => DatePicker::widget([
                                    'model' => $searchModel,
                                    'attribute' => 'date_from',
                                    'attribute2' => 'date_to',
                                    'type' => DatePicker::TYPE_RANGE,
                                    'separator' => '<i class="fas fa-exchange-alt"></i>',
                                    'layout' => $layout,
                                    'pluginOptions' => [
                                        'format' => 'dd.mm.yyyy',
                                        'autoclose' => true,
                                        'todayHighlight' => true,
                                        'todayBtn' => true
                                    ]
                                ]),
                                'options' => ['width'=>'15%'],
                                'headerOptions' => ['style' => 'text-align: center !important; min-width:300px'],
                                'contentOptions' => ['style' => 'text-align: center !important; vertical-align: middle !important;'],
                                'attribute' => 'date',
                                'format' => 'date',
                                'value' => function($model) {
                                    $script = <<< JS
                                        jQuery('input[id=documentsearch-date_from], input[id=documentsearch-date_to]').attr('autocomplete', 'off');
                                    JS;
                                    return $model->date . $this->registerJs($script);
                                }
                            ],
                            [
                                'filter' => Document::getStatusesArray(),
                                'attribute' => 'status',
                                'options' => ['width'=>'10%'],
                                'headerOptions' => ['style' => 'text-align: center !important; min-width:150px'],
                                'contentOptions' => ['style' => 'text-align: center !important; vertical-align: middle !important;'],
                                'format' => 'raw',
                                'value' => function ($model, $key, $index, $column) {
                                    /** @var Document $model */
                                    /** @var \yii\grid\DataColumn $column */
                                    $value = $model->{$column->attribute};
                                    switch ($value) {
                                        case Document::STATUS_ACTIVE:
                                            $class = 'success';
                                            break;
                                        case Document::STATUS_INACTIVE:
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
                    ],

                    ); ?>

                    <?php Pjax::end(); ?>

                </div>
            </div>
        </div>
    </div>
</div>