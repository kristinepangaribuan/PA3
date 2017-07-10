<?php

/* @var $this yii\web\View */

$this->title = 'Beranda';
?>
<div class="site-index">
   <div class="row">
       <div class="col-md-1">
       </div>
        <div class="col-md-10">
            <center><b><h1>Aplikasi Form Rencana Kerja dan Evaluasi Diri Dosen</h1></b>
                <b><h1> Institut Teknologi Del</h1></center></b>
            <div class="box box-solid">
                <div class="box-body">
                    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                            <li data-target="#carousel-example-generic" data-slide-to="1" class=""></li>
                            <li data-target="#carousel-example-generic" data-slide-to="2" class=""></li>
                            <li data-target="#carousel-example-generic" data-slide-to="3" class=""></li>
                        </ol>
                        <div class="carousel-inner">
                            <div class="item active">
                                <img src="<?= Yii::$app->request->baseUrl ?>/background/background1.jpg"
                                     alt="First slide" width="1000" height="600">

                                <div class="carousel-caption">
                                    First Slide
                                </div>
                            </div>
                            <div class="item">
                                <img src="<?= Yii::$app->request->baseUrl ?>/background/background2.jpg"
                                     alt="Second slide" width="1000" height="600">

                                <div class="carousel-caption">
                                    Second Slide
                                </div>
                            </div>
                            <div class="item">
                                <img src="<?= Yii::$app->request->baseUrl ?>/background/background3.jpg"
                                     alt="Third slide" width="1000" height="600">

                                <div class="carousel-caption">
                                    Third Slide
                                </div>
                            </div>
                            <div class="item">
                                <img src="<?= Yii::$app->request->baseUrl ?>/background/background4.jpg"
                                     alt="Fourth slide" width="1000" height="600">

                                <div class="carousel-caption">
                                    Fourth Slide
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
    <div class="col-md-1">
       </div>
</div>
