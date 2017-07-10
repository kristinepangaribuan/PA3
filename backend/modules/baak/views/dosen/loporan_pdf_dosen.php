<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\baak\models\search\DosenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Laporan Dosen';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dosen-index">
    <div class="row">
        
          <div class="box box-solid">
            <div class="box-header with-border">
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                    <div class="col-md-6">
                        <h2>I. Identitas</h2>
                        <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
//                        'dosen_id',
                        'nama_dosen',
                        'email',
                        'alamat',
                        'nidn',
                        [
                            'attribute'=>'golongan_kepangkatan_id',
                            'format' => 'raw',
                            'value' =>$model->golonganKepangkatan->nama_golongan_kepangkatan,
                        ],
                        'status_ikatan_kerja',
                        'aktif_start',
                        'aktif_end',
                        [
                            'attribute'=>'ref_kbk_id',
                            'label' => 'Program Studi',
                            'format' => 'raw',
                            'value' =>$model->refKbk->desc_ind,
                        ],
                    ],
                ]) ?>
                    </div>
                    <div class="col-md-6">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h5><p>
                                    <?=  Html::encode('Bidang Pengajaran: '. $total_sks_pengajaran . ' sks')?><br>
                                    <?=  Html::encode('Bidang Penelitian: ' .$total_sks_penelitian . ' sks')?><br>
                                    <?=  Html::encode('Bidang Pengabdian Masyarakat: '.$total_sks_pengabdian . ' sks')?><br>
                                    <?=  Html::encode('Bidang Pengembagan Instansi: '.$total_sks_pengembangan . ' sks')?>
                                    </p>
                                </h5>
                            </div>
                        </div>
                     </div>
                    
                    <!-- /.col -->
                </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- ./col -->
        
   <div class="penelitian-index">
    <h2 class="box-title"><?= Html::encode('II. Bidang Pendidikan') ?></h2>
    <?php if($dataProviderDosenMatkuliah->totalCount > 0){ $bool = 1; ?> 
        <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h5 class="box-title"><?= Html::encode('Mengajar Matakuliah') ?></h5>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProviderDosenMatkuliah,
                        'summary' => "",
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
//                                'attribute' => 'kuliah_id',
                                'label' => 'Nama Matakuliah',
                                'value' => function ($model){
                                    return $model->kuliah->nama_kul_ind;
                                }
                            ],        
                            [
                                'label' => 'Jumlah SKS',
                                'format' => 'raw',
                                'value' => function($model)use ($dosen) {
                                    return $model->jlh_sks_beban_kerja_dosen;
                                }
                            ],
                            [
                                'label' => 'Status',
                                'format' => 'raw',
                                'value' => function($model){
                                    if($model->status_realisasi == 0){
                                        return 'Tidak Terealisasi';
                                    }else{
                                        return 'Terealisasi';
                                    }
                                }
                            ],        
                        ],
                    ]);
                ?>
                <!-- /.row -->
                </div>
            <!-- ./box-body -->
            </div>
        <!-- /.box -->
        </div>
        <!-- /.col -->  
    </div>
    <?php } ?>
    <?php if($dataProviderAsistenTugas->totalCount > 0){ $bool = 1?>
        <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h5 class="box-title"><?= Html::encode('Asisten Tugas Praktikum') ?></h5>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProviderAsistenTugas,
                        'summary' => "",
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
//                                'attribute' => 'kuliah_id',
                                'label' => 'Nama Matakuliah',
                                'value' => function ($model){
                                    return $model->kuliah->nama_kul_ind;
                                }
                            ],
                            [
                                'label' => 'Jumlah SKS',
                                'format' => 'raw',
                                'value' => function($model)use ($dosen) {
                                    return $model->getJlhSksDosen($model->asisten_tugas_praktikum_id, $dosen->dosen_id);
                                }
                            ],
                            [
                                'label' => 'Status',
                                'format' => 'raw',
                                'value' => function($model){
                                        if($model->status_realisasi == 0){
                                            return 'Tidak Terealisasi';
                                        }else{
                                            return 'Terealisasi';
                                        }
                                }
                            ],        
                        ],
                    ]);
                ?>
            <!-- /.row -->
            </div>
        <!-- ./box-body -->
        </div>
    <!-- /.box -->
    </div>
                        <!-- /.col -->
                    </div>
    <?php } ?>
    
    <?php if($dataProviderBimbinganKuliah->totalCount > 0){ $bool=1;
    ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h5 class="box-title"><?= Html::encode('Melakukan Bimbingan Kuliah') ?></h5>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                            
                            <?=GridView::widget([
                                'dataProvider' => $dataProviderBimbinganKuliah,
                                'summary' => "",
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
//                                    'topik_bimbingan',
                                    [
                                        'label'=>'Topik Bimbingan',
                                        'format'=>'raw',
                                        'value' => function($model){
                                            return $model->topik_bimbingan;
                                        }
                                    ],
                                    [
                                        'label' => 'Jumlah SKS',
                                        'format' => 'raw',
                                        'value' => function($model){
                                            return $model->jlh_sks_bimbingan_kuliah;
                                        }
                                    ],
                                    [
                                        'label' => 'Status',
                                        'format' => 'raw',
                                        'value' => function($model){
                                                if($model->status_realisasi == 0){
                                                    return 'Tidak Terealisasi';
                                                }else{
                                                    return 'Terealisasi';
                                                }
                                        }
                                    ],        
                                ],
                            ]);?>
                            
                    <!-- /.row -->
                    </div>
                <!-- ./box-body -->
                </div>
            <!-- /.box -->
            </div>
        <!-- /.col -->
        </div>
    <?php } ?>
    
    <?php if($dataProviderSeminarTerjadwal->totalCount > 0){ $bool=1;
    ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h5 class="box-title"><?= Html::encode('Melakukan Seminar Terjadwal') ?></h5>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                            <?=GridView::widget([
                                'dataProvider' => $dataProviderSeminarTerjadwal,
                                'summary' => "",
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    [
                                        'label'=>'Nama Bimbingan',
                                        'format'=>'raw',
                                        'value' => function($model){
                                            return $model->nama_seminar;
                                        }
                                    ],
                                    [
                                        'label' => 'Jumlah SKS',
                                        'format' => 'raw',
                                        'value' => function($model)use ($dosen) {
                                            $dosenSeminarTerjadwal= backend\modules\baak\models\DosenSeminarTerjadwal::find()->where(['seminar_terjadwal_id' => $model->seminar_terjadwal_id])->andWhere(['dosen_id' => $dosen->dosen_id])->one();
                                            return $dosenSeminarTerjadwal->jlh_sks_beban_kerja_dosen;
                                        }
                                    ],
                                    [
                                        'label' => 'Status',
                                        'format' => 'raw',
                                        'value' => function($model){
                                                if($model->status_realisasi == 0){
                                                    return 'Tidak Terealisasi';
                                                }else{
                                                    return 'Terealisasi';
                                                }
                                        }
                                    ],        
                                ],
                            ]);?>
                           
                    <!-- /.row -->
                    </div>
                <!-- ./box-body -->
                </div>
            <!-- /.box -->
            </div>
        <!-- /.col -->
        </div>
    <?php } ?>
    
    <?php if($dataProviderMengujiProposal->totalCount > 0){ $bool=1;?>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h5 class="box-title"><?= Html::encode('Menguji Proposal') ?></h5>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                            <?=GridView::widget([
                                'dataProvider' => $dataProviderMengujiProposal,
                                'summary' => "",
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
//                                    'judul_proposal',
                                    [
                                        'label'=>'Judul Proposal',
                                        'format'=>'raw',
                                        'value' => function($model){
                                            return $model->judul_proposal;
                                        }
                                    ],
                                    [
                                        'label' => 'Jumlah SKS',
                                        'format' => 'raw',
                                        'value' => function($model){
                                            return $model->jlh_sks_menguji_proposal;
                                        }
                                    ],
                                    [
                                        'label' => 'Status',
                                        'format' => 'raw',
                                        'value' => function($model){
                                                if($model->status_realisasi == 0){
                                                    return 'Tidak Terealisasi';
                                                }else{
                                                    return 'Terealisasi';
                                                }
                                        }
                                    ],        
                                ],
                            ]);?>
                    <!-- /.row -->
                    </div>
                <!-- ./box-body -->
                </div>
            <!-- /.box -->
            </div>
        <!-- /.col -->
        </div>
    <?php } ?>
    <h2 class="box-title"><?= Html::encode('III. Bidang Penelitian') ?></h2>
    <?php if($dataProviderPenelitian->totalCount > 0){ $bool=1;?>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h5 class="box-title"><?= Html::encode('Melakukan Penelitian') ?></h5>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <?= GridView::widget([
                            'dataProvider' => $dataProviderPenelitian,
                            'summary' => "",
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                [
                                        'label'=>'Judul Penelitian',
                                        'format'=>'raw',
                                        'value' => function($model){
                                            return $model->judul_penelitian;
                                        }
                                ],
                                [
                                    'label' => 'Jumlah SKS',
                                    'format' => 'raw',
                                    'value' => function($model)use ($dosen) {
                                        $dosenPenelitian = backend\modules\baak\models\DosenPenelitian::find()->where(['penelitian_id' => $model->penelitian_id])->andWhere(['dosen_id' => $dosen->dosen_id])->one();
                                        return $dosenPenelitian->jlh_sks_beban_kerja_dosen;
                                    }
                                ],
                                [
                                    'label' => 'Status',
                                    'format' => 'raw',
                                    'value' => function($model){
                                            if($model->status_realisasi == 0){
                                                return 'Tidak Terealisasi';
                                            }else{
                                                return 'Terealisasi';
                                            }
                                    }
                                ],        
                            ],
                        ]);?>
                        
                    <!-- /.row -->
                    </div>
                <!-- ./box-body -->
                </div>
            <!-- /.box -->
            </div>
        <!-- /.col -->
        </div>
    <?php } ?>
    
    <?php if($dataProviderModul->totalCount > 0){ $bool=1;?>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= Html::encode('Membuat Modul Bahan Ajar') ?></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                            
                            <?=GridView::widget([
                                'dataProvider' => $dataProviderModul,
                                'summary' => "",
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
//                                    'nama_modul',
                                    [
                                        'label'=>'Nama Modul',
                                        'format'=>'raw',
                                        'value' => function($model){
                                            return $model->nama_modul;
                                        }
                                    ],
                                    [
                                        'label' => 'Jumlah SKS',
                                        'format' => 'raw',
                                        'value' => function($model)use ($dosen) {
                                            $dosenModulBahanAjar = backend\modules\baak\models\DosenModulBahanAjar::find()->where(['modul_bahan_ajar_id' => $model->modul_bahan_ajar_id])->andWhere(['dosen_id' => $dosen->dosen_id])->one();
                                            return $dosenModulBahanAjar->jlh_sks_beban_kerja_dosen;
                                        }
                                    ],
                                    [
                                        'label' => 'Status',
                                        'format' => 'raw',
                                        'value' => function($model){
                                                if($model->status_realisasi == 0){
                                                    return 'Tidak Terealisasi';
                                                }else{
                                                    return 'Terealisasi';
                                                }
                                        }
                                    ],        
                                ],
                            ]);?>
                            
                    <!-- /.row -->
                    </div>
                <!-- ./box-body -->
                </div>
            <!-- /.box -->
            </div>
        <!-- /.col -->
        </div>
    <?php } ?>
    
    <?php if($dataProviderMakalahSeminar->totalCount > 0){ $bool=1;?>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h5 class="box-title"><?= Html::encode('Menyajikan Malakah Seminar') ?></h5>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                            <?=GridView::widget([
                                'dataProvider' => $dataProviderMakalahSeminar,
                                'summary' => "",
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
//                                    'judul_makalah',
                                    [
                                        'label'=>'Judul Makalah',
                                        'format'=>'raw',
                                        'value' => function($model){
                                            return $model->judul_makalah;
                                        }
                                    ],
                                    [
                                        'label' => 'Jumlah SKS',
                                        'format' => 'raw',
                                        'value' => function($model)use ($dosen) {
                                            $dosenMakalahSeminar = backend\modules\baak\models\DosenMakalahSeminar::find()->where(['makalah_seminar_id' => $model->makalah_seminar_id])->andWhere(['dosen_id' => $dosen->dosen_id])->one();
                                            return $dosenMakalahSeminar->jlh_sks_beban_kerja_dosen;
                                        }
                                    ],
                                    [
                                        'label' => 'Status',
                                        'format' => 'raw',
                                        'value' => function($model){
                                                if($model->status_realisasi == 0){
                                                    return 'Tidak Terealisasi';
                                                }else{
                                                    return 'Terealisasi';
                                                }
                                        }
                                    ],        
                                ],
                            ]);?>
                            
                    <!-- /.row -->
                    </div>
                <!-- ./box-body -->
                </div>
            <!-- /.box -->
            </div>
        <!-- /.col -->
        </div>
    <?php } ?>
    
    <?php if($dataProviderJurnalIlmiah->totalCount > 0){ $bool = 1; ?>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h5 class="box-title"><?= Html::encode('Menulis Jurnal Ilmiah') ?></h5>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProviderJurnalIlmiah,
                        'summary' => "",
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
//                            'judul_jurnal_ilmiah',
                            [
                                'label'=>'Judul Jurnal Ilmiah',
                                'format'=>'raw',
                                'value' => function($model){
                                    return $model->judul_jurnal_ilmiah;
                                }
                            ],
                            [
                                'label' => 'Jumlah SKS',
                                'format' => 'raw',
                                'value' => function($model) use ($dosen) {
                                    $dosenJurnalIlmiah = backend\modules\baak\models\DosenJurnalIlmiah::find()->where(['jurnal_ilmiah_id' => $model->jurnal_ilmiah_id])->andWhere(['dosen_id' => $dosen->dosen_id])->one();
                                    return $dosenJurnalIlmiah->jlh_sks_beban_dosen;
                                }
                            ],
                            [
                                'label' => 'Status',
                                'format' => 'raw',
                                'value' => function($model){
                                        if($model->status_realisasi == 0){
                                            return 'Tidak Terealisasi';
                                        }else{
                                            return 'Terealisasi';
                                        }
                                }
                            ],        
                        ],
                    ]);
                ?>
                <!-- /.row -->
                </div>
            <!-- ./box-body -->
            </div>
        <!-- /.box -->
        </div>
    <!-- /.col -->
    </div>
    <?php } ?>
    
    <h2 class="box-title"><?= Html::encode('III. Bidang Pengabdian Masyarakat') ?></h2>
    <?php if($dataProviderKegiatanPengabdian->totalCount > 0){ $bool = 1; ?>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h5 class="box-title"><?= Html::encode('Melakukan Kegiatan Pengabdian Masyarakat') ?></h5>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProviderKegiatanPengabdian,
                        'summary' => "",
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
//                            'nama_kegiatan',
                            [
                                'label'=>'Nama Kegiatan',
                                'format'=>'raw',
                                'value' => function($model){
                                    return $model->nama_kegiatan;
                                }
                            ],
                            [
                                'label' => 'Jumlah SKS',
                                'format' => 'raw',
                                'value' => function($model)use ($dosen) {
                                    $dosenKegiatan= backend\modules\baak\models\DosenKegiatanPengabdian::find()->where(['kegiatan_pengabdian_masyarakat_id' => $model->kegiatan_pengabdian_masyarakat_id])->andWhere(['dosen_id' => $dosen->dosen_id])->one();
                                    return $dosenKegiatan->jlh_sks_beban_kerja_dosen;
                                }
                            ],
                            [
                                'label' => 'Status',
                                'format' => 'raw',
                                'value' => function($model){
                                        if($model->status_realisasi == 0){
                                            return 'Tidak Terealisasi';
                                        }else{
                                            return 'Terealisasi';
                                        }
                                }
                            ],        
                        ],
                    ]);
                ?>
                <!-- /.row -->
                </div>
            <!-- ./box-body -->
            </div>
        <!-- /.box -->
        </div>
    <!-- /.col -->
    </div>
    <?php } ?>
    
    <h2 class="box-title"><?= Html::encode('IV. Bidang Pengembangan Instansi') ?></h2>
    <?php if($dataProviderDosenJabatan->totalCount > 0){ $bool = 1; ?>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h5 class="box-title"><?= Html::encode('Jabatan Dalam Instansi') ?></h5>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProviderDosenJabatan,
                        'summary' => "",
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
//                                'attribute' => 'struktur_jabatan_id',
                                'label' => 'Jabatan Dosen',
                                'format' => 'raw',
                                'value'=>function($model) {return $model->strukturJabatan->jabatan;},
                                'label' => 'Nama Jabatan'
                            ],
                            [
                                'label' => 'Jumlah SKS',
                                'format' => 'raw',
                                'value' => function($model){
                                    return $model->jlh_sks_beban_kerja_dosen;
                                }
                            ],
                            [
                                'label' => 'Status',
                                'format' => 'raw',
                                'value' => function($model){
                                        if($model->status_realisasi == 0){
                                            return 'Tidak Terealisasi';
                                        }else{
                                            return 'Terealisasi';
                                        }
                                }
                            ],        
                        ],
                    ]);
                ?>
                <!-- /.row -->
                </div>
            <!-- ./box-body -->
            </div>
        <!-- /.box -->
        </div>
    <!-- /.col -->
    </div>
    <?php } ?>
</div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h2 class="box-title">V. Summary</h2>
            </div>
            <!-- /.box-header -->

            <div class="box-footer">
                <div class="row">
                    <div class="col-sm-4 col-xs-6">
                        <div class="description-block border-right">
                            <span class="description-text"><?=  Html::encode('Total SKS FRK : '.$total_sks_frk)?></span><br>
                            <span class="description-text"><?=  Html::encode('Total SKS FED : '.$total_sks_fed)?></span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.box-footer -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>

<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
            </div>
            <br><br>
            <!-- /.box-header -->

             <div class="box-footer">
                <div class="row">
                    <div class="col-md-12">
                        <table>
                            <tr>
                                <td colspan="5">
                                    <center><span class="description-text">Dosen Yang Membuat</span></center>
                                </td>
                                <td colspan="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td colspan="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td colspan="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td colspan="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td colspan="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                
                                <td  colspan="5">
                                    <center><span class="description-text">Menyetujui</span></center>
                                    <center><span class="description-text">Ketua Jurusan/Prodi/Departemen</span></center>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="5">
                                    <br><br>
                                    <center><span class="description-text">(___________________)</span></center>
                                </td>
                                <td colspan="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td colspan="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td colspan="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td colspan="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td colspan="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                               
                            </tr>
                        
                        </table>
                        <!-- /.description-block -->
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.box-footer -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>

<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

