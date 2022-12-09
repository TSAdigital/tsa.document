<?php

use app\models\Document;
use yii\bootstrap4\Html;

if (Yii::$app->user->can('updateDocument', ['document_author' => Document::findOne(['id' => $model->document_id])])) {
    $delete = Html::a('<i class="fas fa-trash-alt text-danger"></i>',
        ['/document/file-delete', 'id' => $model->document_id, 'file' => $model->id],
        ['data' => ['confirm' => "Вы уверены, что хотите удалить файл $model->name?", 'method' => 'post']]
    );
}else{
    $delete = null;
}
$name = Html::a($model->name,['/document/download', 'id' => $document->id, 'file' => $model->id]);

?>

<tr>
    <th scope="row"><?= ++$index + ($current_page > 0 ? ($current_page - 1) * $page_size : 0) ?></th>
    <td><?= $name ?></td>
    <td><?= $model->getFileType($model->file_name) ?? null ?></td>
    <td style="text-align: center"><?= Yii::$app->formatter->asDatetime($model->created_at) ?></td>
    <td style="text-align: center"><?= $delete ?></td>
</tr>



