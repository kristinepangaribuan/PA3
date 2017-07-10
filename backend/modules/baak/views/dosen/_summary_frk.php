<?php
use yii\helpers\Html;
$this->title = 'Summary FRK';
?>
<div class="baak-default-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <div class="col-md-3">
            <?= $this->render('_menu',['item'=>'','dosen'=>$dosen,'total_sks'=>$total_sks]) ?>
        </div>
        <div class="col-md-9">
            <div class="box box-solid">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <div class="info-box">
                            <span class="info-box-icon bg-aqua"><i class="glyphicon glyphicon-file"></i></span>

                            <div class="info-box-content">
                              <span class="info-box-text"><?= Html::a('Bidang Pengajaran', ['/baak/dosen/frk-saya-pengajaran'], ['class' => ''])?></span>
                              <span class="info-box-number"><?=  Html::encode($total_sks_pengajaran . ' SKS')?></span>
                            </div>
                            <!-- /.info-box-content -->
                          </div>
                          <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <div class="info-box">
                            <span class="info-box-icon bg-green"><i class="glyphicon glyphicon-file"></i></span>

                            <div class="info-box-content">
                              <span class="info-box-text"><?= Html::a('Bidang Penelitian', ['/baak/dosen/frk-saya-penelitian'], ['class' => ''])?></span>
                              <span class="info-box-number"><?=  Html::encode($total_sks_penelitian. ' SKS')?></span>
                            </div>
                            <!-- /.info-box-content -->
                          </div>
                          <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <div class="info-box">
                            <span class="info-box-icon bg-yellow"><i class="glyphicon glyphicon-tags"></i></span>

                            <div class="info-box-content">
                              <span class="info-box-text"><?= Html::a('Bidang Pengabdian Masyarakat', ['/baak/dosen/frk-saya-pengabdian-masyarakat'], ['class' => ''])?></span>
                              <span class="info-box-number"><?=  Html::encode($total_sks_pengabdian . ' SKS')?></span>
                            </div>
                            <!-- /.info-box-content -->
                          </div>
                          <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <div class="info-box">
                            <span class="info-box-icon bg-red"><i class="glyphicon glyphicon-user"></i></span>

                            <div class="info-box-content">
                              <span class="info-box-text"><?= Html::a('Bidang Pengembagan Instansi', ['/baak/dosen/frk-saya-pengembangan-instansi'], ['class' => ''])?></span>
                              <span class="info-box-number"><?=  Html::encode($total_sks_pengembangan . ' SKS')?></span>
                            </div>
                            <!-- /.info-box-content -->
                          </div>
                          <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                      </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</div>
