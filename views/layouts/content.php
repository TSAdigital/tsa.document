<?php
/* @var $content string */

use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\helpers\Inflector;

?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header pb-0">
        <div class="container-fluid pb-0">
            <div class="row">
                <div class="col-sm-12 col-md-6 pb-0">
                    <h1 class="m-0 p-0">
                        <?php
                        if (!is_null($this->title)) {
                            echo Html::encode($this->title);
                        } else {
                            echo Inflector::camelize($this->context->id);
                        }
                        ?>
                    </h1>
                    <?php
                    echo Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        'options' => [
                            'class' => 'breadcrumb mb-2 pb-0'
                        ]
                    ]);
                    ?>
                </div><!-- /.col -->
                <div class="col-sm-12 col-md-6 pb-0">
                    <p class="text-md-right mb-0" style="margin-right:-5px">
                        <?php
                        if(isset($this->params['buttons'])){
                            foreach ($this->params['buttons'] as $button){
                                echo $button;
                            }
                        }
                        ?>
                    </p>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <?= $content ?><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>