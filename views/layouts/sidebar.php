<?php

use hail812\adminlte\widgets\Menu;

?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link text-center">
        <i class="far fa-file-alt"></i>
        <span class="brand-text font-weight-light"> <b>TSA</b><sup><em><small>Document</small></em></sup></span>
    </a>


    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2 mb-5">
            <?= Menu::widget([
                'items' => [
                    ['label' => 'НАВИГАЦИЯ', 'header' => true],
                    [
                        'label' => 'Документы',
                        'icon' => 'file',
                        'badge' => '<span class="badge badge-danger right">' . Yii::$app->newDocuments->getNewDocuments() . '</span>',
                        'items' => [
                            ['label' => 'Все', 'url' => ['document/index'], 'active'=> $this->context->getUniqueId() == 'document', 'icon' => 'list'],
                            ['label' => 'Избранные', 'url' => ['favourites/index'], 'active'=> $this->context->getUniqueId() == 'favourites', 'icon' => 'star'],
                        ],
                    ],
                    ['label' => 'СПРАВОЧНИКИ', 'header' => true, 'visible' => Yii::$app->user->can('admin')],
                    ['label' => 'Должности', 'url' => ['position/index'], 'active'=> $this->context->getUniqueId() == 'position', 'icon' => 'id-card-alt', 'visible' => Yii::$app->user->can('admin')],
                    ['label' => 'Сотрудники', 'url' => ['employee/index'], 'active'=> $this->context->getUniqueId() == 'employee', 'icon' => 'id-card', 'visible' => Yii::$app->user->can('admin')],
                    ['label' => 'НАСТРОЙКИ', 'header' => true, 'visible' => Yii::$app->user->can('admin')],
                    ['label' => 'Пользователи', 'url' => ['user/index'], 'active'=> $this->context->getUniqueId() == 'user', 'icon' => 'users', 'visible' => Yii::$app->user->can('admin')],
                ],
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>