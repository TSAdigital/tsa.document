<tr>
    <th scope="row"><?php echo $index+1; ?></th>
    <td><?= $model->document->getUsers($model->user_id) ?></td>
    <td><?= $model->user->employee->position_name ?? null ?></td>
    <td style="text-align: center"><?= Yii::$app->formatter->asDatetime($model->created_at) ?></td>
</tr>



