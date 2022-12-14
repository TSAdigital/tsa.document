<?php

use app\models\Favourites;
use kartik\date\DatePicker;
use yii\bootstrap4\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\FavouritesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Избранное';
$this->params['breadcrumbs'][] = $this->title;

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
                                        'attribute' => 'document_name',
                                        'headerOptions' => ['style' => 'min-width:250px'],
                                        'contentOptions' => ['style' => 'vertical-align: middle !important;'],
                                        'options' => ['width'=>'50%'],
                                        'format'=>'raw',
                                        'value' => function($model)
                                        {
                                            return Html::a($model->document->name, ['document/view', 'id' => $model->document->id], ['class'=>'no-pjax']);
                                        }
                                    ],

                                    [
                                        'attribute' => 'document_author',
                                        'headerOptions' => ['style' => 'min-width:250px'],
                                        'contentOptions' => ['style' => 'vertical-align: middle !important;'],
                                        'options' => ['width'=>'30%'],
                                        'format'=>'raw',
                                        'value' => function($model)
                                        {
                                            return Html::a($model->document->user->employee_name, ['site/profile', 'id' => $model->document->user->id], ['class'=>'no-pjax']);
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
                                        'options' => ['width'=>'20%'],
                                        'headerOptions' => ['style' => 'text-align: center !important; min-width:300px'],
                                        'contentOptions' => ['style' => 'text-align: center !important; vertical-align: middle !important;'],
                                        'attribute' => 'document_date',
                                        'format' => 'date',
                                        'value' => function($model) {
                                            $script = <<< JS
                                        jQuery('input[id=documentsearch-date_from], input[id=documentsearch-date_to]').attr('autocomplete', 'off');
                                    JS;
                                            return $model->document->date . $this->registerJs($script);
                                        }
                                    ],

                                    [
                                        'class' => ActionColumn::className(),
                                        'urlCreator' => function ($action, Favourites $model, $key, $index, $column) {
                                            return Url::toRoute([$action, 'id' => $model->id]);
                                         },
                                        'buttons' => [
                                            'delete' => function ($action, Favourites $model, $key) {
                                                return Html::a('<i class="fas fa-trash-alt text-danger"></i>', ['delete', 'id' => $model->id], [
                                                    'data' => [
                                                        'confirm' => 'Вы уверены, что хотите удалить этот документ из избранного?',
                                                        'method' => 'post',
                                                    ],
                                                ]);
                                            }
                                        ],
                                        'template' => '{delete}'
                                    ],
                                ],
                            ]); ?>

                    <?php Pjax::end(); ?>

                </div>
            </div>
        </div>
    </div>
</div>
