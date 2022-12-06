<?php

/** @var yii\web\View $this */
/** @var app\models\UploadForm $model */
/** @var app\models\Document $document */

use yii\bootstrap4\Html;

$this->title = 'Новый файл для: ' . $document->name;
$this->params['breadcrumbs'][] = ['label' => 'Документы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $document->name, 'url' => ['document/view', 'id' => $document->id]];
$this->params['breadcrumbs'][] = $this->title;
$this->params['buttons'] = [
    'save' => Html::submitButton('<i class="far fa-save text-green"></i>Сохранить', ['class' => 'btn btn-app', 'form'=> 'upload']),
    'undo' => Html::a('<i class="far fa-arrow-alt-circle-left text-muted"></i>Вернуться', ['document/view', 'id' => $document->id], ['class' => 'btn btn-app'])
];
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <?= $this->render('_form_upload', [
                        'model' => $model,
                        'document' => $document
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>
