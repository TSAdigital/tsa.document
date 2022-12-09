<?php
use yii\bootstrap4\Html;
?>

<tr>
    <th scope="row"><?= ++$index + ($current_page > 0 ? ($current_page - 1) * $page_size : 0) ?></th>
    <td><?= Html::a($model->document->name,
            ['document/view', 'id' => $model->document->id])?></td>
    <td style="text-align: center"><?= $model->document->date ?></td>
</tr>