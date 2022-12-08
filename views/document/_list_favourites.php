<?php
use yii\bootstrap4\Html;
?>

<tr>
    <th scope="row"><?= $index+1; ?></th>
    <td><?= Html::a($model->document->name,
            ['document/view', 'id' => $model->document->id])?></td>
    <td style="text-align: center"><?= $model->document->date ?></td>
</tr>