<?php

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\widgets\DetailView;
use yii\widgets\ListView;

/** @var yii\web\View $this */
/** @var app\models\Document $model */
/** @var app\models\Viewed $dataViewed */
/** @var app\models\Viewed $discussion */
/** @var app\models\Viewed $dataNoViewed */
/** @var app\models\UploadForm $file */

$this->title = StringHelper::truncate($model->name,50,'...');
$this->params['breadcrumbs'][] = ['label' => 'Документы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['buttons'] = [
    'viewed' => ($model->author != Yii::$app->user->identity->getId() and empty($model->isViewed($model->id))) ? Html::a('<i class="fas fa-check-circle text-info"></i> Ознакомлен', ['viewed', 'id' => $model->id], ['class' => 'btn btn-app', 'data' => [
        'confirm' => 'Вы уверены, что хотите ознакомиться с данным документом?',
        'method' => 'post',
    ]]) : null,
    'file' => \Yii::$app->user->can('updateDocument', ['document_author' => $model]) ? Html::a('<i class="fas fa-paperclip text-green"></i> Файл', ['upload', 'id' => $model->id], ['class' => 'btn btn-app']) : null,
    'update' => \Yii::$app->user->can('updateDocument', ['document_author' => $model]) ? Html::a('<i class="fas fa-edit text-primary"></i> Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-app']) : null,
    'delete' => \Yii::$app->user->can('admin') ? Html::a('<i class="fas fa-trash-alt text-danger"></i> Удалить', ['delete', 'id' => $model->id], [
        'class' => 'btn btn-app',
        'data' => [
            'confirm' => 'Вы уверены, что хотите удалить этот документ?',
            'method' => 'post',
        ],
    ]) : null,
    'undo' => Html::a('<i class="far fa-arrow-alt-circle-left text-muted"></i>Вернуться', ['document/index'], ['class' => 'btn btn-app'])
];
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills" id="myTab">
                        <li class="nav-item"><a class="nav-link active" href="#base" data-toggle="tab">Основное</a></li>
                        <li class="nav-item"><a class="nav-link" href="#case" data-toggle="tab">Обсуждения <span class="badge badge-danger"><?= $discussions_count ?></span></a></li>
                        <li class="nav-item"><a class="nav-link" href="#viewed" data-toggle="tab">События</a></li>
                    </ul>
                </div>
                <div class="card-body pb-1">
                    <div class="tab-content">
                        <div class="active tab-pane" id="base">
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    [
                                        'attribute' => 'name',
                                        'captionOptions' => ['width' => '200px'],
                                    ],
                                    'description',
                                    'date',
                                    [
                                        'attribute' => 'author',
                                        'format' => 'raw',
                                        'value' => Html::a($model->user->employee_name, ['site/profile', 'id' => $model->author]),
                                    ],
                                    [
                                        'attribute' => 'resolution',
                                        'format' => 'raw',
                                        'value' => !empty($model->getUsers($model->resolution)) ? $model->getUsers($model->resolution) : 'Все сотрудники',
                                    ],
                                    [
                                        'attribute' => 'files',
                                        'format' => 'raw',
                                        'value' => !empty($file) ? $file : null,
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
                        <div class="tab-pane" id="case">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">

                                            <?= ListView::widget([
                                                'dataProvider' => $dataDiscussions,
                                                'viewParams' => ['id' => $model->id],
                                                'itemView' => '_list_discussions',
                                            ]);
                                            ?>

                                        </div>

                                        <div class="card-footer py-3 border-0" style="background-color: #f8f9fa;">

                                            <?php $form = ActiveForm::begin(); ?>

                                            <div class="d-flex flex-start w-100">
                                                <div class="form-outline w-100">
                                                    <?= $form->field($discussion, 'text')->textarea(['class' => 'form-control', 'rows' => 4])?>
                                                </div>
                                            </div>

                                            <div class="mt-2 pt-1">
                                                <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
                                            </div>

                                            <?php ActiveForm::end(); ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="viewed">
                            <div class="accordion" id="accordionExample">
                                <div class="card">
                                    <div class="card-header" id="headingOne">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left p-0 m-0" type="button"
                                                    data-toggle="collapse" data-target="#collapseOne"
                                                    aria-expanded="true" aria-controls="collapseOne">
                                                    Ознакомились с документом
                                            </button>
                                        </h2>
                                    </div>

                                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                         data-parent="#accordionExample">
                                        <div class="card-body">

                                            <?php $template = '
                                            {summary}  
                                            <div class="table-responsive">
                                            <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col" style="width: 50%">Сотрудник</th>
                                                <th scope="col" style="width: 35%">Должность</th>
                                                <th scope="col" style="width: 15%; text-align: center">Дата и время</th>
                                               </tr>
                                            </thead>
                                            <tbody>
                                                {items}
                                                </tbody>
                                            </table>
                                            </div>
                                            {pager}
                                            '; ?>

                                            <?= ListView::widget([
                                                'dataProvider' => $dataViewed,
                                                'layout' => $template,
                                                'itemView' => '_list',
                                            ]);
                                            ?>


                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingTwo">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left collapsed p-0 m-0" type="button"
                                                    data-toggle="collapse" data-target="#collapseTwo"
                                                    aria-expanded="false" aria-controls="collapseTwo">
                                                    Не ознакомились с документом
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                        <div class="card-body">

                                            <?php $template = '
                                            {summary}
                                            <div class="table-responsive">
                                            <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col" style="width: 50%">Сотрудник</th>
                                                <th scope="col" style="width: 50%">Должность</th>
                                               </tr>
                                            </thead>
                                            <tbody>
                                                {items}
                                                </tbody>
                                            </table>
                                            </div>
                                            {pager}
                                            '; ?>

                                            <?= ListView::widget([
                                                'dataProvider' => $dataNoViewed,
                                                'layout' => $template,
                                                'itemView' => '_list_no_viewed',
                                            ]);
                                            ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>