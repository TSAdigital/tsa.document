<?php

use yii\bootstrap5\Html;

?>

<tr>
    <th scope="row"><?= ++$index + ($current_page > 0 ? ($current_page - 1) * $page_size : 0) ?></th>
    <td><?= Html::a($model->employee_name, ['site/profile', 'id' => $model->id]) ?></td>
    <td><?= $model->employee->position_name ?? null ?></td>
</tr>
