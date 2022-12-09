<tr>
    <th scope="row"><?= ++$index + ($current_page > 0 ? ($current_page - 1) * $page_size : 0) ?></th>
    <td><?= $model->document->getUsers($model->user_id) ?></td>
    <td><?= $model->user->employee->position_name ?? null ?></td>
    <td style="text-align: center"><?= Yii::$app->formatter->asDatetime($model->created_at) ?></td>
</tr>



