<?php

use yii\bootstrap5\Html;

?>

<tr>
    <th scope="row"><?= $index+1; ?></th>
    <td><?= Html::a($model->employee_name, ['site/profile', 'id' => $model->id]) ?></td>
    <td><?= $model->employee->position_name ?? null ?></td>
</tr>
