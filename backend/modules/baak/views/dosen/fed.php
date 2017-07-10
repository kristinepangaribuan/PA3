<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\baak\models\models_search\PenelitianSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Form Evaluasi Diri ' . $dosen['nama_dosen'];
$this->params['breadcrumbs'][] = $this->title;

$bool = null;
?>
<div class="penelitian-index">
    <h2><?= Html::encode($this->title) ?></h2>
    
    <?php if($dataProviderDosenMatkuliah->totalCount > 0){ $bool = 1; ?> 
        <div class="row">
        <div class="col-md-12">
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
                                        return $model->kuliah->nama_kul_ind;
                                }
                            ],
                            [
                                'label' => 'Jumlah SKS Dosen',
                                'format' => 'raw',
                                'value' => function($model){
                                    return $model->jlh_sks_beban_kerja_dosen;
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
                                            $url = Url::to(['/baak/dosen-matakuliah/dosen-matakuliah-view/', 'id' => $model->dosen_matakuliah_id]);
                                            return '<center>'.Html::a('<i class="glyphicon glyphicon-hand-up"></i> View', $url, [
                                                        'class'=>'btn btn-danger',
                                                        'data-toggle'=>'tooltip', 
                                                        'title'=>'View'
                                                    ]).'</center>';
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
                            [
                                'label' => 'Jumlah SKS Dosen',
                                'format' => 'raw',
                                'value' => function($model)use ($dosen) {
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
                                                $url = Url::to(['/baak/asisten-tugas-praktikum/asisten-tugas-praktikum-view/', 'id' => $model->asisten_tugas_praktikum_id]);
                                                return '<center>'.Html::a('<i class="glyphicon glyphicon-hand-up"></i> View', $url, [
                                                        'class'=>'btn btn-danger',
                                                        'data-toggle'=>'tooltip', 
                                                        'title'=>'View'
                                                    ]).'</center>';
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
                                    [
                                        'label' => 'Jumlah SKS Dosen',
                                        'format' => 'raw',
                                        'value' => function($model){
                                            return $model->jlh_sks_bimbingan_kuliah;
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
                                                $url = Url::to(['/baak/bimbingan-kuliah/bimbingan-kuliah-view/', 'id' => $model->bimbingan_kuliah_id]);
                                                return '<center>'.Html::a('<i class="glyphicon glyphicon-hand-up"></i> View', $url, [
                                                        'class'=>'btn btn-danger',
                                                        'data-toggle'=>'tooltip', 
                                                        'title'=>'View'
                                                    ]).'</center>';
                                            },
                                        ]
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
                                    [
                                        'label' => 'Jumlah SKS Dosen',
                                        'format' => 'raw',
                                        'value' => function($model)use ($dosen) {
                                            $dosenSeminarTerjadwal= backend\modules\baak\models\DosenSeminarTerjadwal::find()->where(['seminar_terjadwal_id' => $model->seminar_terjadwal_id])->andWhere(['dosen_id' => $dosen->dosen_id])->one();
//                                            var_dump($dosenSeminarTerjadwal);
//                                            die();
                                            return $dosenSeminarTerjadwal['jlh_sks_beban_kerja_dosen'];
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
                                                $url = Url::to(['/baak/seminar-terjadwal/seminar-terjadwal-view/', 'id' => $model->seminar_terjadwal_id]);
                                                return '<center>'.Html::a('<i class="glyphicon glyphicon-hand-up"></i> View', $url, [
                                                        'class'=>'btn btn-danger',
                                                        'data-toggle'=>'tooltip', 
                                                        'title'=>'View'
                                                    ]).'</center>';
                                            },
                                        ],
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
                                    [
                                        'label' => 'Jumlah SKS Dosen',
                                        'format' => 'raw',
                                        'value' => function($model){
                                            return $model->jlh_sks_menguji_proposal;
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
                                                $url = Url::to(['/baak/menguji-proposal/menguji-proposal-view/', 'id' => $model->menguji_proposal_id]);
                                                return '<center>'.Html::a('<i class="glyphicon glyphicon-hand-up"></i> View', $url, [
                                                        'class'=>'btn btn-danger',
                                                        'data-toggle'=>'tooltip', 
                                                        'title'=>'View'
                                                    ]).'</center>';
                                            },
                                        ],
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
                                'judul_penelitian',
                                [
                                    'label' => 'Jumlah SKS Dosen',
                                    'format' => 'raw',
                                    'value' => function($model)use ($dosen) {
                                        $dosenPenelitian = backend\modules\baak\models\DosenPenelitian::find()->where(['penelitian_id' => $model->penelitian_id])->andWhere(['dosen_id' => $dosen->dosen_id])->one();
//                                        var_dump($dosenPenelitian);
//                                        die();
                                        return $dosenPenelitian['jlh_sks_beban_kerja_dosen'];
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
                                            $url = Url::to(['/baak/penelitian/penelitian-view/', 'id' => $model->penelitian_id]);
                                            return '<center>'.Html::a('<i class="glyphicon glyphicon-hand-up"></i> View', $url, [
                                                        'class'=>'btn btn-danger',
                                                        'data-toggle'=>'tooltip', 
                                                        'title'=>'View'
                                                    ]).'</center>';
                                        },
                                    ]
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
                                    [
                                        'label' => 'Jumlah SKS Dosen',
                                        'format' => 'raw',
                                        'value' => function($model)use ($dosen) {
                                            $dosenModulBahanAjar = backend\modules\baak\models\DosenModulBahanAjar::find()->where(['modul_bahan_ajar_id' => $model->modul_bahan_ajar_id])->andWhere(['dosen_id' => $dosen->dosen_id])->one();
                                            return $dosenModulBahanAjar['jlh_sks_beban_kerja_dosen'];
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
                                                $url = Url::to(['/baak/modul-bahan-ajar/modul-bahan-ajar-view/', 'id' => $model->modul_bahan_ajar_id]);
                                                return '<center>'.Html::a('<i class="glyphicon glyphicon-hand-up"></i> View', $url, [
                                                        'class'=>'btn btn-danger',
                                                        'data-toggle'=>'tooltip', 
                                                        'title'=>'View'
                                                    ]).'</center>';
                                            },
                                        ],
                                    ],                                ],
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
                                    [
                                        'label' => 'Jumlah SKS Dosen',
                                        'format' => 'raw',
                                        'value' => function($model)use ($dosen) {
                                            $dosenMakalahSeminar = backend\modules\baak\models\DosenMakalahSeminar::find()->where(['makalah_seminar_id' => $model->makalah_seminar_id])->andWhere(['dosen_id' => $dosen->dosen_id])->one();
                                            return $dosenMakalahSeminar['jlh_sks_beban_kerja_dosen'];
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
                                                $url = Url::to(['/baak/makalah-seminar/makalah-seminar-view/', 'id' => $model->makalah_seminar_id]);
                                                return '<center>'.Html::a('<i class="glyphicon glyphicon-hand-up"></i> View', $url, [
                                                        'class'=>'btn btn-danger',
                                                        'data-toggle'=>'tooltip', 
                                                        'title'=>'View'
                                                    ]).'</center>';
                                            },
                                        ],
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
                            [
                                'label' => 'Jumlah SKS Dosen',
                                'format' => 'raw',
                                'value' => function($model) use ($dosen) {
                                    $dosenJurnalIlmiah = backend\modules\baak\models\DosenJurnalIlmiah::find()->where(['jurnal_ilmiah_id' => $model->jurnal_ilmiah_id])->andWhere(['dosen_id' => $dosen->dosen_id])->one();
                                    return $dosenJurnalIlmiah['jlh_sks_beban_dosen'];
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
                                        $url = Url::to(['/baak/jurnal-ilmiah/jurnal-ilmiah-view/', 'id' => $model->jurnal_ilmiah_id]);
                                        return '<center>'.Html::a('<i class="glyphicon glyphicon-hand-up"></i> View', $url, [
                                                        'class'=>'btn btn-danger',
                                                        'data-toggle'=>'tooltip', 
                                                        'title'=>'View'
                                                    ]).'</center>';
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
                            [
                                'label' => 'Jumlah SKS Dosen',
                                'format' => 'raw',
                                'value' => function($model)use ($dosen) {
                                    $dosenKegiatan= backend\modules\baak\models\DosenKegiatanPengabdian::find()->where(['kegiatan_pengabdian_masyarakat_id' => $model->kegiatan_pengabdian_masyarakat_id])->andWhere(['dosen_id' => $dosen->dosen_id])->one();
                                    return $dosenKegiatan['jlh_sks_beban_kerja_dosen'];
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
                                                $url = Url::to(['/baak/kegiatan-pengabdian-masyarakat/kegiatan-pengabdian-masyarakat-view/', 'id' => $model->kegiatan_pengabdian_masyarakat_id]);
                                                return '<center>'.Html::a('<i class="glyphicon glyphicon-hand-up"></i> View', $url, [
                                                        'class'=>'btn btn-danger',
                                                        'data-toggle'=>'tooltip', 
                                                        'title'=>'View'
                                                    ]).'</center>';
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
                                'label' => 'Jumlah SKS Dosen',
                                'format' => 'raw',
                                'value' => function($model){
                                    return $model->jlh_sks_beban_kerja_dosen;
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
                                        $url = Url::to(['/baak/dosen-jabatan/dosen-jabatan-view/', 'id' => $model->dosen_jabatan_id]);
                                        return '<center>'.Html::a('<i class="glyphicon glyphicon-hand-up"></i> View', $url, [
                                                        'class'=>'btn btn-danger',
                                                        'data-toggle'=>'tooltip', 
                                                        'title'=>'View'
                                                    ]).'</center>';
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

<?php if($bool!=null){ ?>
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Total Sks</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.box-header -->

            <div class="box-footer">
                <div class="row">
                    <div class="col-sm-4 col-xs-6">
                        <div class="description-block border-right">
                            <span class="description-text">TOTAL SKS FED Terealisasi</span>
                            <h5 class="description-header"><?=$total_sks_terealisasi?> sks</h5>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    
                    <div class="col-sm-4 col-xs-6">
                        <div class="description-block border-right">
                            <span class="description-text">TOTAL SKS FED Tidak Terealisasi</span>
                            <h5 class="description-header"><?=$total_sks_tidak_terealisasi?> sks</h5>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    
                    <div class="col-sm-4 col-xs-6">
                        <div class="description-block border-right">
                            
                            <?php 
                                $status = backend\modules\baak\models\Dosen::getStatusFed($dosen['dosen_id']);
                                $status_selesai = backend\modules\baak\models\Dosen::getStatusFedSelesai($dosen['dosen_id']);
                                if($status>0){ ?>
                                    <span class="description-text">FED Telah Disubmit</span>
                             <?php   }else if($status_selesai>0){ ?>
                                     <span class="description-text">FED Telah Diapprove</span>
                             <?php }else{
                            ?>
                                <h5><?= Html::a('Submit Semua FED', ['submit-all-fed', 'status'=>'Pengajuan FED'], ['class' => 'btn btn-primary', 'data' => [
                                'confirm' => 'Apakah anda yakin akan mensubmit semua FED Anda?',
                                'method' => 'post',
                            ],]) ?></h5>
                                <?php } ?>
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
<?php }else{ ?>
    <div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Tidak ada FED anda, silahkan buat FRK anda terlebih dahulu</h3>
            </div>
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>
<?php } ?> 