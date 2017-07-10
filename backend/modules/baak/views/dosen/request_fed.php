<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\tabs\TabsX;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\baak\models\models_search\PenelitianSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Request FED ' . $dosen['nama_dosen'];
$this->params['breadcrumbs'][] = $this->title;

$bool = null;

$this->registerJs("$(function() {
   $('#modalButton').click(function(e) {
//     e.preventDefault();
     $('#modal').modal('show').find('#modalContent')
     .load($(this).attr('value'));
   });
});");
?>

<div class="penelitian-index">
    <h2><?= Html::encode($this->title) ?></h2>
    <?php if($dataProviderDosenMatkuliah->totalCount > 0){ $bool = 1; ?> 
        <div class="row">
        <div class="col-md-12">
            <?php
                yii\bootstrap\Modal::begin(
                    [
                        'id' =>'modal',
                        'header' => '<h3>Berikan Alasan Penolakan</h3>',
                        'size' => 'modal-lg'
                    ]);
                    echo "<div id='modalContent'></div>";
                yii\bootstrap\Modal::end();
            ?>
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Html::encode('Mengajar Matakuliah') ?></h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProviderDosenMatkuliah,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'kuliah_id',
                                'label' => 'Nama Matakuliah',
                                'value' => function ($model){
//                                    if($model->kuliah->nama_kul_ind != NULL)
                                        return $model->kuliah->nama_kul_ind;
//                                    else
//                                        return ""
                                }
                                ],
//                            'jlh_mhs_bimbingan_kuliah',
                            [
                                'label' => 'Jumlah SKS',
                                'format' => 'raw',
                                'value' => function($model)use ($dosen) {
//                                    $dosenPenelitian = backend\modules\baak\models\DosenPenelitian::find()->where(['penelitian_id' => $model->penelitian_id])->andWhere(['dosen_id' => $dosen->dosen_id])->one();
                                    return $model->jlh_sks_beban_kerja_dosen . "";
                                }
                                    ],
                                            [
                                    'label' => 'Status',
                                    'format' => 'raw',
                                    'value' => function($model){
                                        if($model->status_realisasi==1)
                                            return 'Terealisasi';
                                        else {
                                            return 'Tidak Terealisasi';
                                        }
                                    }
                                ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{view}',
                                        'buttons' => [
                                            'view' => function ($url, $model) {
                                                $url = Url::to(['/baak/dosen-matakuliah/dosen-matakuliah-view-kprodi-frk/', 'id' => $model->dosen_matakuliah_id]);
                                                return Html::a('<i class="glyphicon glyphicon-hand-up"></i> Lihat Rincian', $url, ['title' => 'view']);
                                            },
                                                ]
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
                    <h3 class="box-title"><?= Html::encode('Asisten Tugas Praktikum') ?></h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProviderAsistenTugas,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'kuliah_id',
                                'label' => 'Nama Matakuliah',
                                'value' => function ($model){
                                    return $model->kuliah->nama_kul_ind;
                                }
                                ],
//                            'jlh_mhs_bimbingan_kuliah',
                            [
                                'label' => 'Jumlah SKS',
                                'format' => 'raw',
                                'value' => function($model)use ($dosen) {
//                                    $dosenPenelitian = backend\modules\baak\models\DosenPenelitian::find()->where(['penelitian_id' => $model->penelitian_id])->andWhere(['dosen_id' => $dosen->dosen_id])->one();
                                    return $model->getJlhSksDosen($model->asisten_tugas_praktikum_id, $dosen->dosen_id);
                                }
                                    ],
                                            [
                                    'label' => 'Status',
                                    'format' => 'raw',
                                    'value' => function($model){
                                        if($model->status_realisasi==1)
                                            return 'Terealisasi';
                                        else {
                                            return 'Tidak Terealisasi';
                                        }
                                    }
                                ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{view}',
                                        'buttons' => [
                                            'view' => function ($url, $model) {
                                                $url = Url::to(['/baak/asisten-tugas-praktikum/asisten-tugas-praktikum-view-kprodi-frk/', 'id' => $model->asisten_tugas_praktikum_id]);
                                                return Html::a('<i class="glyphicon glyphicon-hand-up"></i> Lihat Rincian', $url, ['title' => 'view']);
                                            },
                                                ]
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
                        <h3 class="box-title"><?= Html::encode('Melakukan Bimbingan Kuliah') ?></h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                            
                            <?=GridView::widget([
                                'dataProvider' => $dataProviderBimbinganKuliah,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    'topik_bimbingan',
//                                    'jlh_mhs_bimbingan_kuliah',
                                    [
                                        'label' => 'Jumlah SKS',
                                        'format' => 'raw',
                                        'value' => function($model)use ($dosen) {
                                            return $model->jlh_sks_bimbingan_kuliah . "";
                                        }
                                    ],
                                            [
                                    'label' => 'Status',
                                    'format' => 'raw',
                                    'value' => function($model){
                                        if($model->status_realisasi==1)
                                            return 'Terealisasi';
                                        else {
                                            return 'Tidak Terealisasi';
                                        }
                                    }
                                ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{view}',
                                        'buttons' => [
                                            'view' => function ($url, $model) {
                                                $url = Url::to(['/baak/bimbingan-kuliah/bimbingan-kuliah-view-kprodi-frk/', 'id' => $model->bimbingan_kuliah_id]);
                                                return Html::a('<i class="glyphicon glyphicon-hand-up"></i> Lihat Rincian', $url, ['title' => 'view']);
                                            },
                                        ]
                                    ],
//                                    [
//                                        'class' => 'yii\grid\CheckboxColumn',
//                                        'checkboxOptions' => function($model) {
//                                            return ['value' => $model->bimbingan_kuliah_id];
//                                        }
//                                    ],
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
                        <h3 class="box-title"><?= Html::encode('Melakukan Seminar Terjadwal') ?></h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                            <?=GridView::widget([
                                'dataProvider' => $dataProviderSeminarTerjadwal,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    'nama_seminar',
//                                    'jlh_mhs_seminar',
                                    [
                                        'label' => 'Jumlah SKS',
                                        'format' => 'raw',
                                        'value' => function($model)use ($dosen) {
                                            $dosenSeminarTerjadwal= backend\modules\baak\models\DosenSeminarTerjadwal::find()->where(['seminar_terjadwal_id' => $model->seminar_terjadwal_id])->andWhere(['dosen_id' => $dosen->dosen_id])->one();
                                            return $dosenSeminarTerjadwal->jlh_sks_beban_kerja_dosen . "";
                                        }
                                    ],
                                            [
                                    'label' => 'Status',
                                    'format' => 'raw',
                                    'value' => function($model){
                                        if($model->status_realisasi==1)
                                            return 'Terealisasi';
                                        else {
                                            return 'Tidak Terealisasi';
                                        }
                                    }
                                ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{view}',
                                        'buttons' => [
                                            'view' => function ($url, $model) {
                                                $url = Url::to(['/baak/seminar-terjadwal/seminar-terjadwal-view-kprodi-frk', 'id' => $model->seminar_terjadwal_id]);
                                                return Html::a('<i class="glyphicon glyphicon-hand-up"></i> Lihat Rincian', $url, ['title' => 'view']);
                                            },
                                        ],
                                    ],
//                                    [
//                                        'class' => 'yii\grid\CheckboxColumn',
//                                        'checkboxOptions' => function($model) {
//                                            return ['value' => $model->seminar_terjadwal_id];
//                                        }
//                                    ],
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
                        <h3 class="box-title"><?= Html::encode('Menguji Proposal') ?></h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                            <?=GridView::widget([
                                'dataProvider' => $dataProviderMengujiProposal,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    'judul_proposal',
                                    //'jlh_mhs_proposal',
//                                  'jlh_sks_menguji_proposal',
                                    [
                                        'label' => 'Jumlah SKS',
                                        'format' => 'raw',
                                        'value' => function($model)use ($dosen) {
                                            return $model->jlh_sks_menguji_proposal. " sks";
                                        }
                                    ],
                                            [
                                    'label' => 'Status',
                                    'format' => 'raw',
                                    'value' => function($model){
                                        if($model->status_realisasi==1)
                                            return 'Terealisasi';
                                        else {
                                            return 'Tidak Terealisasi';
                                        }
                                    }
                                ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{view}',
                                        'buttons' => [
                                            'view' => function ($url, $model) {
                                                $url = Url::to(['/baak/menguji-proposal/menguji-proposal-view-kprodi-frk/', 'id' => $model->menguji_proposal_id]);
                                                return Html::a('<i class="glyphicon glyphicon-hand-up"></i> Lihat Rincian', $url, ['title' => 'view']);
                                            },
                                        ],
                                    ],
//                                    [
//                                        'class' => 'yii\grid\CheckboxColumn',
//                                        'checkboxOptions' => function($model) {
//                                            return ['value' => $model->menguji_proposal_id];
//                                        }
//                                    ],
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
 
    <?php if($dataProviderPenelitian->totalCount > 0){ $bool=1;?>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= Html::encode('Melakukan Penelitian') ?></h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <?= GridView::widget([
                            'dataProvider' => $dataProviderPenelitian,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                //'jenis_penelitian_id',
                                'judul_penelitian',
                                [
                                    'label' => 'Jumlah SKS',
                                    'format' => 'raw',
                                    'value' => function($model)use ($dosen) {
                                        $dosenPenelitian = backend\modules\baak\models\DosenPenelitian::find()->where(['penelitian_id' => $model->penelitian_id])->andWhere(['dosen_id' => $dosen->dosen_id])->one();
                                        return $dosenPenelitian->jlh_sks_beban_kerja_dosen . "";
                                    }
                                ],
                                        [
                                    'label' => 'Status',
                                    'format' => 'raw',
                                    'value' => function($model){
                                        if($model->status_realisasi==1)
                                            return 'Terealisasi';
                                        else {
                                            return 'Tidak Terealisasi';
                                        }
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{view}',
                                    'buttons' => [
                                        'view' => function ($url, $model) {
                                            $url = Url::to(['/baak/penelitian/penelitian-view-kprodi-frk/', 'id' => $model->penelitian_id]);
                                            return Html::a('<i class="glyphicon glyphicon-hand-up"></i> Lihat Rincian', $url, ['title' => 'view']);
                                        },
                                    ]
                                ],
//                                [
//                                    'class' => 'yii\grid\CheckboxColumn',
//                                    'checkboxOptions' => function($model) {
//                                        return ['value' => $model->penelitian_id];
//                                    }
//                                ],
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

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                            
                            <?=GridView::widget([
                                'dataProvider' => $dataProviderModul,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    'nama_modul',
                                    //'tahapan_modul_id',
                                    [
                                        'label' => 'Jumlah SKS',
                                        'format' => 'raw',
                                        'value' => function($model)use ($dosen) {
                                            $dosenModulBahanAjar = backend\modules\baak\models\DosenModulBahanAjar::find()->where(['modul_bahan_ajar_id' => $model->modul_bahan_ajar_id])->andWhere(['dosen_id' => $dosen->dosen_id])->one();
                                            return $dosenModulBahanAjar->jlh_sks_beban_kerja_dosen . "";
                                        }
                                    ],
                                            [
                                    'label' => 'Status',
                                    'format' => 'raw',
                                    'value' => function($model){
                                        if($model->status_realisasi==1)
                                            return 'Terealisasi';
                                        else {
                                            return 'Tidak Terealisasi';
                                        }
                                    }
                                ],
//                                    [
//                                        'attribute' => 'jlh_targer',
//                                        'label' => 'Jumlah Target',
//                                        'format' => 'raw',
//                                        'value' => function ($model) {
//                                            return $model->jlh_targer . "%";
//                                        }
//                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{view}',
                                        'buttons' => [
                                            'view' => function ($url, $model) {
                                                $url = Url::to(['/baak/modul-bahan-ajar/modul-bahan-ajar-view-kprodi-frk/', 'id' => $model->modul_bahan_ajar_id]);
                                                return Html::a('<i class="glyphicon glyphicon-hand-up"></i> Lihat Rincian', $url, ['title' => 'view']);
                                            },
                                        ],
                                    ],
//                                    ['class' => 'yii\grid\CheckboxColumn',
//                                        'checkboxOptions' => function($model) {
//                                            return ['value' => $model->modul_bahan_ajar_id];
//                                        }
//                                    ],
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
                        <h3 class="box-title"><?= Html::encode('Menyajikan Malakah Seminar') ?></h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                            <?=GridView::widget([
                                'dataProvider' => $dataProviderMakalahSeminar,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    'judul_makalah',
//                                    'tingkatan_makalah',
                                    [
                                        'label' => 'Jumlah SKS',
                                        'format' => 'raw',
                                        'value' => function($model)use ($dosen) {
                                            $dosenMakalahSeminar = backend\modules\baak\models\DosenMakalahSeminar::find()->where(['makalah_seminar_id' => $model->makalah_seminar_id])->andWhere(['dosen_id' => $dosen->dosen_id])->one();
                                            return $dosenMakalahSeminar->jlh_sks_beban_kerja_dosen . "";
                                        }
                                    ],
                                            [
                                    'label' => 'Status',
                                    'format' => 'raw',
                                    'value' => function($model){
                                        if($model->status_realisasi==1)
                                            return 'Terealisasi';
                                        else {
                                            return 'Tidak Terealisasi';
                                        }
                                    }
                                ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{view}',
                                        'buttons' => [
                                            'view' => function ($url, $model) {
                                                $url = Url::to(['/baak/makalah-seminar/makalah-seminar-view-kprodi-frk/', 'id' => $model->makalah_seminar_id]);
                                                return Html::a('<i class="glyphicon glyphicon-hand-up"></i> Lihat Rincian', $url, ['title' => 'view']);
                                            },
                                        ],
                                    ],
//                                    [
//                                        'class' => 'yii\grid\CheckboxColumn',
//                                        'checkboxOptions' => function($model) {
//                                            return ['value' => $model->makalah_seminar_id];
//                                        }
//                                    ],
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
                    <h3 class="box-title"><?= Html::encode('Menulis Jurnal Ilmiah') ?></h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProviderJurnalIlmiah,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'judul_jurnal_ilmiah',
//                            'penerbit_jurnal_ilmiah',
                            [
                                'label' => 'Jumlah SKS',
                                'format' => 'raw',
                                'value' => function($model) use ($dosen) {
                                    $dosenJurnalIlmiah = backend\modules\baak\models\DosenJurnalIlmiah::find()->where(['jurnal_ilmiah_id' => $model->jurnal_ilmiah_id])->andWhere(['dosen_id' => $dosen->dosen_id])->one();
                                    return $dosenJurnalIlmiah->jlh_sks_beban_dosen . "";
                                }
                            ],
                                    [
                                    'label' => 'Status',
                                    'format' => 'raw',
                                    'value' => function($model){
                                        if($model->status_realisasi==1)
                                            return 'Terealisasi';
                                        else {
                                            return 'Tidak Terealisasi';
                                        }
                                    }
                                ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{view}',
                                'buttons' => [
                                    'view' => function ($url, $model) {
                                        $url = Url::to(['/baak/jurnal-ilmiah/jurnal-ilmiah-view-kprodi-frk/', 'id' => $model->jurnal_ilmiah_id]);
                                        return Html::a('<i class="glyphicon glyphicon-hand-up"></i> Lihat Rincian', $url, ['title' => 'view']);
                                    },
                                ],
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
    
    <?php if($dataProviderKegiatanPengabdian->totalCount > 0){ $bool = 1; ?>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Html::encode('Melakukan Kegiatan Pengabdian Masyarakat') ?></h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProviderKegiatanPengabdian,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'nama_kegiatan',
//                            'kategori_kegiatan',
                            [
                                'label' => 'Jumlah SKS',
                                'format' => 'raw',
                                'value' => function($model)use ($dosen) {
                                    $dosenKegiatan= backend\modules\baak\models\DosenKegiatanPengabdian::find()->where(['kegiatan_pengabdian_masyarakat_id' => $model->kegiatan_pengabdian_masyarakat_id])->andWhere(['dosen_id' => $dosen->dosen_id])->one();
                                    return $dosenKegiatan->jlh_sks_beban_kerja_dosen . "";
                                }
                                    ],
                                            [
                                    'label' => 'Status',
                                    'format' => 'raw',
                                    'value' => function($model){
                                        if($model->status_realisasi==1)
                                            return 'Terealisasi';
                                        else {
                                            return 'Tidak Terealisasi';
                                        }
                                    }
                                ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{view}',
                                        'buttons' => [
                                            'view' => function ($url, $model) {
                                                $url = Url::to(['/baak/kegiatan-pengabdian-masyarakat/kegiatan-pengabdian-masyarakat-view-kprodi-frk/', 'id' => $model->kegiatan_pengabdian_masyarakat_id]);
                                                return Html::a('<i class="glyphicon glyphicon-hand-up"></i> Lihat Rincian', $url, ['title' => 'view']);
                                            },
                                                ]
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
    
    <?php if($dataProviderDosenJabatan->totalCount > 0){ $bool = 1; ?>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Html::encode('Jabatan Dalam Instansi') ?></h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProviderDosenJabatan,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            
                            [
                                'attribute' => 'struktur_jabatan_id',
                                'format' => 'raw',
                                'value'=>function($model) {return $model->strukturJabatan->jabatan;},
                                'label' => 'Nama Jabatan'
                            ],
                            [
                                'label' => 'Jumlah SKS',
                                'format' => 'raw',
                                'value' => function($model)use ($dosen) {
                                    return $model->jlh_sks_beban_kerja_dosen . "";
                                }
                                    ],
                                            [
                                    'label' => 'Status',
                                    'format' => 'raw',
                                    'value' => function($model){
                                        if($model->status_realisasi==1)
                                            return 'Terealisasi';
                                        else {
                                            return 'Tidak Terealisasi';
                                        }
                                    }
                                ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{view}',
                                        'buttons' => [
                                            'view' => function ($url, $model) {
                                                $url = Url::to(['/baak/dosen-jabatan/dosen-jabatan-view-kprodi-frk/', 'id' => $model->dosen_jabatan_id]);
                                                return Html::a('<i class="glyphicon glyphicon-hand-up"></i> Lihat Rincian', $url, ['title' => 'view']);
                                            },
                                                ]
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


<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"></h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>

                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <!-- /.box-header -->

            <div class="box-footer">
                <div class="row">
                    <div class="col-sm-4 col-xs-6">
                        <?php if($status == 'Pengajuan FED'){?>
                        <div class="description-block border-right">
                            <span class="description-text"><b>TOTAL SKS Request FED</b></span>
                            <h5 class="description-header"><?=$total_sks?> SKS</h5>
                        </div>
                        <?php }else{ ?>
                        <div class="description-block border-right">
                            <span class="description-text"><b>TOTAL SKS Approve FED</b></span>
                            <h5 class="description-header"><?=$total_sks?> SKS</h5>
                        </div>
                        <?php } ?>
                        <!-- /.description-block -->
                    </div>
                    <?php if($status == 'Pengajuan FED'){?>
                    <div class="col-sm-4 col-xs-6">
                        <div class="description-block border-right">
                            <span class="description-text">Approve Semua FED</span>
                            <h5><?= Html::a('Approve', ['process-all-fed','id'=>$dosen['dosen_id'], 'status'=>'Approve FED'], ['class' => 'btn btn-primary',
                                    'data' => [
                                        'confirm' => "Apakah Anda yakin akan meng-approve semua FED '.$dosen->nama_dosen .' .?",
                                        'method' => 'post',
                                    ],
                                ]) ?></h5>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <div class="col-sm-4 col-xs-6">
                        <div class="description-block border-right">
                            <span class="description-text">Tolak Semua FED</span>
                            <h5><?= Html::button('Tolak FED', ['value'=>  Url::toRoute(['dosen/rejectfed', 'id'=>$dosen['dosen_id'], 'status'=>'Reject FED']),'class' => 'btn btn-danger', 'id'=>'modalButton']) ?></h5>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <?php } ?>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.box-footer -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>