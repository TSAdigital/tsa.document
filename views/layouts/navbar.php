<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <?= Html::a( 'О проекте', ['/site/about'], ['class' => 'nav-link']); ?>
        </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto inline">
        <li class="nav-item">
            <?= Html::a( !empty(Yii::$app->user->identity->username) ? '<i class="far fa-user"></i> ' . Yii::$app->user->identity->getEmployee_name()  : 'Гость' , ['site/profile', 'id' => Yii::$app->user->identity->id], ['class' => 'nav-link']) ?>
        </li>
        <li class="nav-item">
            <?= !empty(Yii::$app->user->identity->username) ? Html::a('<i class="fas fa-sign-out-alt"></i>', ['/site/logout'], ['data-method' => 'post', 'class' => 'nav-link']) : Html::a('<i class="fas fa-sign-in-alt"></i>', ['/site/login'], ['class' => 'nav-link']) ?>
        </li>
    </ul>
</nav>
<!-- /.navbar -->