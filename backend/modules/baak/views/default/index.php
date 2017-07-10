<?php
use yii\helpers\Html;
?>
<div class="baak-default-index">
    <h1><?= $this->context->action->uniqueId ?></h1>
    <div class="row">
        <div class="col-md-8">
            <div class="box box-solid">
                <div class="box-body">
                    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                            <li data-target="#carousel-example-generic" data-slide-to="1" class=""></li>
                            <li data-target="#carousel-example-generic" data-slide-to="2" class=""></li>
                        </ol>
                        <div class="carousel-inner">
                            <div class="item active">
                                <img src="<?= Yii::$app->request->baseUrl ?>/background/background1.PNG"
                                     alt="First slide">

                                <div class="carousel-caption">
                                    First Slide
                                </div>
                            </div>
                            <div class="item">
                                <img src="<?= Yii::$app->request->baseUrl ?>/background/background2.PNG"
                                     alt="Second slide">

                                <div class="carousel-caption">
                                    Second Slide
                                </div>
                            </div>
                            <div class="item">
                                <img src="<?= Yii::$app->request->baseUrl ?>/background/background3.PNG"
                                     alt="Third slide">

                                <div class="carousel-caption">
                                    Third Slide
                                </div>
                            </div>
                            <div class="item">
                                <img src="<?= Yii::$app->request->baseUrl ?>/background/background4.PNG"
                                     alt="Third slide">

                                <div class="carousel-caption">
                                    Fourth Slide
                                </div>
                            </div>
                            <div class="item">
                                <img src="<?= Yii::$app->request->baseUrl ?>/background/background5.PNG"
                                     alt="Third slide">

                                <div class="carousel-caption">
                                    Fifth Slide
                                </div>
                            </div>
                        </div>
                        <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                            <span class="fa fa-angle-left"></span>
                        </a>
                        <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                            <span class="fa fa-angle-right"></span>
                        </a>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</div>
