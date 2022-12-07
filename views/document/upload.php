<?php

/** @var yii\web\View $this */
/** @var app\models\UploadForm $model */
/** @var app\models\Document $document */

use yii\bootstrap4\Html;
use yii\helpers\StringHelper;

$this->title = 'Новый файл для: ' . StringHelper::truncate($document->name,50,'...');
$this->params['breadcrumbs'][] = ['label' => 'Документы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => StringHelper::truncate($document->name,30,'...'), 'url' => ['document/view', 'id' => $document->id]];
$this->params['breadcrumbs'][] = $this->title;
$this->params['buttons'] = [
    'save' => Html::submitButton('<i class="far fa-save text-green"></i>Сохранить', ['class' => 'btn btn-app', 'form'=> 'upload']),
    'undo' => Html::a('<i class="far fa-arrow-alt-circle-left text-muted"></i>Вернуться', ['document/view', 'id' => $document->id], ['class' => 'btn btn-app'])
];
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="callout callout-danger">
                <h5><b>ВНИМАНИЕ!</b></h5>
                <p class="mb-1">Не рекомендуется загружать личные документы сотрудников, например - копия паспорта.</p>
                <p>Также не рекомендуется загружать секретные и конфиденциальные документы.</p>
            </div>
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
