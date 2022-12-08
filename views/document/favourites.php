<?php

/** @var yii\web\View $this */

use yii\widgets\ListView;

$this->title = 'Избранное';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body pb-0">
                    <?php
                        $template = '
                            {summary}  
                            <div class="table-responsive">
                            <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col" style="width: 80%">Наименование</th>
                                    <th scope="col" style="width: 20%;  text-align: center">Дата документа</th>
                                    </th>                                                   
                                </tr>
                            </thead>
                            <tbody>
                                {items}
                                </tbody>
                            </table>
                            </div>
                            {pager}
                        ';
                    ?>

                    <?= ListView::widget([
                        'dataProvider' => $dataFavourites,
                        'layout' => $template,
                        'emptyText' => '<p>Вы пока ничего не добавляли в избранное</p>',
                        'itemView' => '_list_favourites',
                    ]);
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>
