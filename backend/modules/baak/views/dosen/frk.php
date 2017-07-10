<?php
use yii\helpers\Html;


$this->title = 'Form Rencana Kerja';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="baak-frk">

    <h2><?= Html::encode($this->title .' Dosen') ?></h2>
      
      <div class="row">
        <div class="col-md-12">
          <div class="box box-solid">
            <!-- /.box-header -->
            <div class="box-body">
              <div class="box-group" id="accordion">
                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                <div class="panel box box-primary">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                        Bidang Pendidikan
                      </a>
                    </h4>
                  </div>
                  <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="box-body">
                      <div class="col-md-6">
                    <?= Html::a('<i class="fa fa-fw fa-arrow-circle-right"></i>Mengajar Matakuliah', ['/baak/dosen-matakuliah/dosen-matakuliah-add/'], ['class' => 'btn btn-block btn-social btn-bitbucket']) ?> 
                    <?= Html::a('<i class="fa fa-fw fa-arrow-circle-right"></i>Asisten Tugas/Praktikum', ['/baak/asisten-tugas-praktikum/asisten-tugas-praktikum-add'], ['class' => 'btn btn-block btn-social btn-dropbox']) ?> 
                    <?= Html::a('<i class="fa fa-fw fa-arrow-circle-right"></i>Bimbingan Kuliah', ['/baak/bimbingan-kuliah/bimbingan-kuliah-add/'], ['class' => 'btn btn-block btn-social btn-facebook']) ?> 
                    
                    </div>
                        <div class="col-md-6">
                            <?= Html::a('<i class="fa fa-fw fa-arrow-circle-right"></i>Seminar Terjadwal', ['/baak/seminar-terjadwal/seminar-terjadwal-add/'], ['class' => 'btn btn-block btn-social btn-github']) ?> 
                    <?= Html::a('<i class="fa fa-fw fa-arrow-circle-right"></i>Menguji Proposal', ['/baak/menguji-proposal/menguji-proposal-add/'], ['class' => 'btn btn-block btn-social btn-bitbucket']) ?>    
                    <!--?= Html::a('<i class="fa fa-fw fa-arrow-circle-right"></i>Pembimbingan Dosen', ['/baak/pembimbingan-dosen/pembimbingan-dosen-add/'], ['class' => 'btn btn-block btn-social btn-bitbucket']) ?-->    

                    </div>
                    <!--</div>-->
                  </div>
                </div>
                <div class="panel box box-danger">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                        Bidang Penelitian
                      </a>
                    </h4>
                  </div>
                  <div id="collapseTwo" class="panel-collapse collapse">
                    <div class="box-body">
                      <div class="col-md-6">
                        <?= Html::a('<i class="fa fa-fw fa-arrow-circle-right"></i>Penelitian Dosen', ['/baak/penelitian/penelitian-add/'], ['class' => 'btn btn-block btn-social btn-bitbucket']) ?>    
                          <!--?= Html::a('<i class="fa fa-fw fa-arrow-circle-right"></i>Naskah Buku', ['/baak/naskah-buku/naskah-buku-add/'], ['class' => 'btn btn-block btn-social btn-dropbox']) ?-->
                          <!--?= Html::a('<i class="fa fa-fw fa-arrow-circle-right"></i>Media Massa', ['/baak/media-massa/media-massa-add/'], ['class' => 'btn btn-block btn-social btn-dropbox']) ?-->
                          <?= Html::a('<i class="fa fa-fw fa-arrow-circle-right"></i>Modul Bahan Ajar', ['/baak/modul-bahan-ajar/modul-bahan-ajar-add/'], ['class' => 'btn btn-block btn-social btn-flickr']) ?>

                        </div>
                        <div class="col-md-6">
                            <!--?= Html::a('<i class="fa fa-fw fa-arrow-circle-right"></i>Hak Paten', ['/baak/hak-paten/hak-paten-add/'], ['class' => 'btn btn-block btn-social btn-foursquare']) ?-->
                            <?= Html::a('<i class="fa fa-fw fa-arrow-circle-right"></i>Penyaji Makalah', ['/baak/makalah-seminar/makalah-seminar-add/'], ['class' => 'btn btn-block btn-social btn-github']) ?>
                            <!--?= Html::a('<i class="fa fa-fw fa-arrow-circle-right"></i>Orasi Ilmiah', ['/baak/orasi-ilmiah/orasi-ilmiah-add/'], ['class' => 'btn btn-block btn-social btn-facebook']) ?-->
                            <?= Html::a('<i class="fa fa-fw fa-arrow-circle-right"></i>Jurnal Ilmiah', ['/baak/jurnal-ilmiah/jurnal-ilmiah-add/'], ['class' => 'btn btn-block btn-social btn-facebook']) ?>

                        </div>
                    </div>
                  </div>
                </div>
                <div class="panel box box-success">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                        Bidang Pengabdian Masyarakat
                      </a>
                    </h4>
                  </div>
                  <div id="collapseThree" class="panel-collapse collapse">
                    <div class="box-body">
                        <div class="col-md-12">
                            <?= Html::a('<i class="fa fa-fw fa-arrow-circle-right"></i>Kegiatan Pengabdian Masyarakat', ['/baak/kegiatan-pengabdian-masyarakat/kegiatan-pengabdian-masyarakat-add/'], ['class' => 'btn btn-block btn-social btn-bitbucket']) ?>    
                        </div>
                    </div>
                  </div>
                </div>
                <div class="panel box box-success">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                        Bidang Pengembangan Instansi
                      </a>
                    </h4>
                  </div>
                  <div id="collapseFour" class="panel-collapse collapse">
                      <div class="box-body">
                        <div class="col-md-12">
                            <?= Html::a('<i class="fa fa-fw fa-arrow-circle-right"></i>Jabatan Dosen', ['/baak/dosen-jabatan/dosen-jabatan-add/'], ['class' => 'btn btn-block btn-social btn-bitbucket']) ?>    
                        </div>
                      </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
      <!-- /.row -->
</div>