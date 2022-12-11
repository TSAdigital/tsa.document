<?php

/* @var $this yii\web\View */
/* @var $user app\models\User */
/* @var $actionsHistory yii\data\ActiveDataProvider */

use \yii\helpers\HtmlPurifier;

$this->title = 'Профиль';
$this->params['breadcrumbs'][] = 'Профиль пользователя';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-5">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <i class="far fa-user" style="font-size: 24px;"></i>
                    </div>

                    <h3 class="profile-username text-center"><?= HtmlPurifier::process($user->employee_name) ?></h3>
                    <p class="text-muted text-center"><?= HtmlPurifier::process($user->getRolesName()) ?></p>
                    <a href="mailto:<?= HtmlPurifier::process($user->email) ?>" class="btn btn-primary btn-block"><b>Отправить сообщение</b></a>
                </div>
            </div>
        </div>
        <div class="col-md-7">

        </div>
    </div>
</div>