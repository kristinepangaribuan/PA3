<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\baak\models\search\DosenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Laporan FRK & FED '.$model->nama_dosen;


$script = <<< JS
//here you reight all your javascript stuff
    $('#tahun_ajaran').change(function(){
        var tahun_ajaran = $(this).val();
        if(tahun_ajaran.length == 0){
            $("#semester").attr('disabled', true); 
            $("#submit").attr('disabled', true);   
        }else{
            $("#semester").attr('disabled', false);   
        }
    })
    $('#semester').change(function(){
        var semester = $(this).val();
        if(semester.length == 0){
            $("#submit").attr('disabled', true);   
        }else{
            $("#submit").attr('disabled', false);   
        }
    })    
JS;
$this->registerJs($script);

?>
<div class="dosen-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <!--<div class="col-md-12">-->
          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title"><?= Html::encode('Dosen '. $model->nama_dosen) ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="col-md-7">
                    <div class="col-md-6">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3><p><?=  Html::encode('Bidang Pengajaran')?></p></h3>
                              <h4><?=  Html::encode($total_sks_pengajaran . ' SKS')?></h4>
                            </div>
                            <div class="icon">
                              <i class="ion ion-bag"></i>
                            </div>
                        </div>
                     </div>
                    <div class="col-md-6">
                        <div class="small-box bg-green">
                            <div class="inner">
                                <h3><p><?=  Html::encode('Bidang Penelitian')?></p></h3>
                              <h4><?=  Html::encode($total_sks_penelitian . ' SKS')?></h4>
                            </div>
                            <div class="icon">
                              <i class="ion ion-bag"></i>
                            </div>
                        </div>
                     </div>
                    <div class="col-md-6">
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h3><p><?=  Html::encode('Bidang Pengabdian Masyarakat')?></p></h3>
                              <h4><?=  Html::encode($total_sks_pengabdian . ' SKS')?></h4>
                            </div>
                            <div class="icon">
                              <i class="ion ion-bag"></i>
                            </div>
                        </div>
                     </div>
                    <div class="col-md-6">
                        <div class="small-box bg-red">
                            <div class="inner">
                                <h3><p><?=  Html::encode('Bidang Pengembagan Instansi')?></p></h3>
                              <h4><?=  Html::encode($total_sks_pengembangan . ' SKS')?></h4>
                            </div>
                            <div class="icon">
                              <i class="ion ion-bag"></i>
                            </div>
                        </div>
                     </div>
                    <!-- /.col -->
                </div>
                <div class="col-md-5">
                    <?php $form = ActiveForm::begin(); ?>
                        <?= $form->field($model, 'tahun_ajaran_id')->label('Tahun Ajaran')->dropDownList(\yii\helpers\ArrayHelper::map($TahunAjaran, 'tahun_ajaran_id', 'tahun_ajaran'), ['value'=>$semester->tahunAjaran->tahun_ajaran_id]) ?>
                        <?= $form->field($model, 'semester_id')->label('Semester')->dropDownList(['Gasal'=>'Gasal', 'Genap'=>'Genap'], ['value'=>$sem]) ?>
                        <div class="form-group">
                            <?= Html::submitButton('Cari', ['class' => 'btn btn-primary', 'id'=>'submit']) ?>
                            <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
                        </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
            <!-- /.box-body -->
          </div>
        </div>
          <!-- /.box -->
<!--        </div>-->
        <!-- ./col -->

   <div class="penelitian-index">
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
                    <?php 
                    echo GridView::widget([
                            'dataProvider' => $dataProviderDosenMatkuliah,
                            'columns' => [
                                [
                                    'class' => 'kartik\grid\ExpandRowColumn',
                                    'value' => function($model, $key, $index, $column) {
                                        return GridView::ROW_COLLAPSED;
                                    },
                                    'detail' => function($model, $key, $index, $column) {
                                        $searchModelDokumenBuktiMatakuliah = backend\modules\baak\models\DokumenBuktiDosenMatakuliah::find()->where(['dosen_matakuliah_id'=>$model->dosen_matakuliah_id]);
                                        $dataProviderDokumen = new ActiveDataProvider([
                                            'query' => $searchModelDokumenBuktiMatakuliah,
                                        ]);

                                        return Yii::$app->controller->renderPartial('dokumen_bukti_matakuliah', [
                                            'dataProviderDokumen' => $dataProviderDokumen,
                                            'dosen_matakuliah_id' => $model->dosen_matakuliah_id,
                                            'model' => $model,
                                        ]);
                                    },

                                ],
                                [
                                    'attribute' => 'kuliah_id',
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
                                [
                            //      'class' => 'yii\grid\ActionColumn',
                                    'format' => 'raw',
                                    'value' => function($model){
                                        $url = Url::to(['/baak/dosen-matakuliah/dosen-matakuliah-view-kprodi-frk/', 'id' => $model->dosen_matakuliah_id]);
                                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => 'view']);
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
//                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                    'class' => 'kartik\grid\ExpandRowColumn',
                                    'value' => function($model, $key, $index, $column) {
                                        return GridView::ROW_COLLAPSED;
                                    },
                                    'detail' => function($model, $key, $index, $column) {
                                        $searchModelDokumenBuktiAsisten = backend\modules\baak\models\DokumenBuktiAsisten::find()->where(['asisten_tugas_praktikum_id'=>$model->asisten_tugas_praktikum_id]);
                                        $dataProviderDokumen = new ActiveDataProvider([
                                            'query' => $searchModelDokumenBuktiAsisten,
                                        ]);

                                        return Yii::$app->controller->renderPartial('dokumen_bukti_asisten', [
                                            'dataProviderDokumen' => $dataProviderDokumen,
//                                            'asisten_praktikum_id' => $model->asisten_tugas_praktikum_id,
                                            'model' => $model,
                                        ]);
                                    },

                                ],
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
                                        if($model->status_realisasi == 0){
                                            return 'Tidak Terealisasi';
                                        }else{
                                            return 'Terealisasi';
                                        }
                                }
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{view}',
                                        'buttons' => [
                                            'view' => function ($url, $model) {
                                                $url = Url::to(['/baak/asisten-tugas-praktikum/asisten-tugas-praktikum-view-kprodi-frk/', 'id' => $model->asisten_tugas_praktikum_id]);
                                                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => 'view']);
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
                                    [
                                    'class' => 'kartik\grid\ExpandRowColumn',
                                    'value' => function($model, $key, $index, $column) {
                                        return GridView::ROW_COLLAPSED;
                                    },
                                    'detail' => function($model, $key, $index, $column) {
                                        $searchModelDokumenBuktiBimbingan = backend\modules\baak\models\DokumenBuktiBimbinganKuliah::find()->where(['bimbingan_kuliah_id'=>$model->bimbingan_kuliah_id]);
                                        $dataProviderDokumen = new ActiveDataProvider([
                                            'query' => $searchModelDokumenBuktiBimbingan,
                                        ]);

                                        return Yii::$app->controller->renderPartial('dokumen_bukti_bimbingan', [
                                            'dataProviderDokumen' => $dataProviderDokumen,
                                            'model' => $model,
                                        ]);
                                    },

                                ],
                                    'topik_bimbingan',
                                    [
                                        'label' => 'Jumlah SKS Dosen',
                                        'format' => 'raw',
                                        'value' => function($model)use ($dosen) {
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
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{view}',
                                        'buttons' => [
                                            'view' => function ($url, $model) {
                                                $url = Url::to(['/baak/bimbingan-kuliah/bimbingan-kuliah-view-kprodi-frk/', 'id' => $model->bimbingan_kuliah_id]);
                                                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => 'view']);
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
                                    [
                                    'class' => 'kartik\grid\ExpandRowColumn',
                                    'value' => function($model, $key, $index, $column) {
                                        return GridView::ROW_COLLAPSED;
                                    },
                                    'detail' => function($model, $key, $index, $column) {
                                        $searchModelDokumenBuktiSeminar = backend\modules\baak\models\DokumenBuktiSeminarTerjadwal::find()->where(['seminar_terjadwal_id'=>$model->seminar_terjadwal_id]);
                                        $dataProviderDokumen = new ActiveDataProvider([
                                            'query' => $searchModelDokumenBuktiSeminar,
                                        ]);

                                        return Yii::$app->controller->renderPartial('dokumen_bukti_seminar_terjadwal', [
                                            'dataProviderDokumen' => $dataProviderDokumen,
                                            'model' => $model,
                                        ]);
                                    },

                                ],
                                    'nama_seminar',
                                    
                                    [
                                        'label' => 'Jumlah SKS Dosen',
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
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{view}',
                                        'buttons' => [
                                            'view' => function ($url, $model) {
                                                $url = Url::to(['/baak/seminar-terjadwal/seminar-terjadwal-view-kprodi-frk', 'id' => $model->seminar_terjadwal_id]);
                                                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => 'view']);
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
//                                    ['class' => 'yii\grid\SerialColumn'],
                                    [
                                    'class' => 'kartik\grid\ExpandRowColumn',
                                    'value' => function($model, $key, $index, $column) {
                                        return GridView::ROW_COLLAPSED;
                                    },
                                    'detail' => function($model, $key, $index, $column) {
                                        $searchModelDokumenBuktiProposal = backend\modules\baak\models\DokumenBuktiMengujiProposal::find()->where(['menguji_proposal_id'=>$model->menguji_proposal_id]);
                                        $dataProviderDokumen = new ActiveDataProvider([
                                            'query' => $searchModelDokumenBuktiProposal,
                                        ]);

                                        return Yii::$app->controller->renderPartial('dokumen_bukti_menguji_proposal', [
                                            'dataProviderDokumen' => $dataProviderDokumen,
                                            'model' => $model,
                                        ]);
                                    },

                                ],
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
                                        if($model->status_realisasi == 0){
                                            return 'Tidak Terealisasi';
                                        }else{
                                            return 'Terealisasi';
                                        }
                                }
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{view}',
                                        'buttons' => [
                                            'view' => function ($url, $model) {
                                                $url = Url::to(['/baak/menguji-proposal/menguji-proposal-view-kprodi-frk/', 'id' => $model->menguji_proposal_id]);
                                                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => 'view']);
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
//                                ['class' => 'yii\grid\SerialColumn'],
                                [
                                    'class' => 'kartik\grid\ExpandRowColumn',
                                    'value' => function($model, $key, $index, $column) {
                                        return GridView::ROW_COLLAPSED;
                                    },
                                    'detail' => function($model, $key, $index, $column) {
                                        $searchModelDokumenBuktiPenelitian = backend\modules\baak\models\DokumenBuktiPenelitian::find()->where(['penelitian_id'=>$model->penelitian_id]);
                                        $dataProviderDokumen = new ActiveDataProvider([
                                            'query' => $searchModelDokumenBuktiPenelitian,
                                        ]);

                                        return Yii::$app->controller->renderPartial('dokumen_bukti_penelitian', [
                                            'dataProviderDokumen' => $dataProviderDokumen,
                                            'model' => $model,
                                        ]);
                                    },

                                ],
                                'judul_penelitian',
                                
                                [
                                    'label' => 'Jumlah SKS Dosen',
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
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{view}',
                                    'buttons' => [
                                        'view' => function ($url, $model) {
                                            $url = Url::to(['/baak/penelitian/penelitian-view-kprodi-frk/', 'id' => $model->penelitian_id]);
                                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => 'view']);
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
//                                    ['class' => 'yii\grid\SerialColumn'],
                                    [
                                    'class' => 'kartik\grid\ExpandRowColumn',
                                    'value' => function($model, $key, $index, $column) {
                                        return GridView::ROW_COLLAPSED;
                                    },
                                    'detail' => function($model, $key, $index, $column) {
                                        $searchModelDokumenBuktiModul = backend\modules\baak\models\DokumenBuktiModul::find()->where(['modul_bahan_ajar_id'=>$model->modul_bahan_ajar_id]);
                                        $dataProviderDokumen = new ActiveDataProvider([
                                            'query' => $searchModelDokumenBuktiModul,
                                        ]);

                                        return Yii::$app->controller->renderPartial('dokumen_bukti_modul', [
                                            'dataProviderDokumen' => $dataProviderDokumen,
                                            'model' => $model,
                                        ]);
                                    },

                                ],
                                    'nama_modul',
                                    
                                    [
                                        'label' => 'Jumlah SKS Dosen',
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
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{view}',
                                        'buttons' => [
                                            'view' => function ($url, $model) {
                                                $url = Url::to(['/baak/modul-bahan-ajar/modul-bahan-ajar-view-kprodi-frk/', 'id' => $model->modul_bahan_ajar_id]);
                                                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => 'view']);
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
//                                    ['class' => 'yii\grid\SerialColumn'],
                                    [
                                    'class' => 'kartik\grid\ExpandRowColumn',
                                    'value' => function($model, $key, $index, $column) {
                                        return GridView::ROW_COLLAPSED;
                                    },
                                    'detail' => function($model, $key, $index, $column) {
                                        $searchModelDokumenBuktiMakalah = backend\modules\baak\models\DokumenBuktiMakalahSeminar::find()->where(['makalah_seminar_id'=>$model->makalah_seminar_id]);
                                        $dataProviderDokumen = new ActiveDataProvider([
                                            'query' => $searchModelDokumenBuktiMakalah,
                                        ]);

                                        return Yii::$app->controller->renderPartial('dokumen_bukti_makalah', [
                                            'dataProviderDokumen' => $dataProviderDokumen,
                                            'model' => $model,
                                        ]);
                                    },

                                ],
                                    'judul_makalah',
                                    
                                    [
                                        'label' => 'Jumlah SKS Dosen',
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
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{view}',
                                        'buttons' => [
                                            'view' => function ($url, $model) {
                                                $url = Url::to(['/baak/makalah-seminar/makalah-seminar-view-kprodi-frk/', 'id' => $model->makalah_seminar_id]);
                                                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => 'view']);
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
//                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                    'class' => 'kartik\grid\ExpandRowColumn',
                                    'value' => function($model, $key, $index, $column) {
                                        return GridView::ROW_COLLAPSED;
                                    },
                                    'detail' => function($model, $key, $index, $column) {
                                        $searchModelDokumenBuktiJurnal = backend\modules\baak\models\DokumenBuktiJurnalIlmiah::find()->where(['jurnal_ilmiah_id'=>$model->jurnal_ilmiah_id]);
                                        $dataProviderDokumen = new ActiveDataProvider([
                                            'query' => $searchModelDokumenBuktiJurnal,
                                        ]);

                                        return Yii::$app->controller->renderPartial('dokumen_bukti_jurnal', [
                                            'dataProviderDokumen' => $dataProviderDokumen,
                                            'model' => $model,
                                        ]);
                                    },

                                ],
                            'judul_jurnal_ilmiah',
                            
                            [
                                'label' => 'Jumlah SKS Dosen',
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
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{view}',
                                'buttons' => [
                                    'view' => function ($url, $model) {
                                        $url = Url::to(['/baak/jurnal-ilmiah/jurnal-ilmiah-view-kprodi-frk/', 'id' => $model->jurnal_ilmiah_id]);
                                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => 'view']);
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
//                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                    'class' => 'kartik\grid\ExpandRowColumn',
                                    'value' => function($model, $key, $index, $column) {
                                        return GridView::ROW_COLLAPSED;
                                    },
                                    'detail' => function($model, $key, $index, $column) {
                                        $searchModelDokumenBuktiKegiatan = backend\modules\baak\models\DokumenBuktiKegiatanPengabdian::find()->where(['kegiatan_pengabdian_masyarakat_id'=>$model->kegiatan_pengabdian_masyarakat_id]);
                                        $dataProviderDokumen = new ActiveDataProvider([
                                            'query' => $searchModelDokumenBuktiKegiatan,
                                        ]);

                                        return Yii::$app->controller->renderPartial('dokumen_bukti_kegiatan', [
                                            'dataProviderDokumen' => $dataProviderDokumen,
                                            'model' => $model,
                                        ]);
                                    },

                                ],
                            'nama_kegiatan',
                           
                            [
                                'label' => 'Jumlah SKS Dosen',
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
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{view}',
                                        'buttons' => [
                                            'view' => function ($url, $model) {
                                                $url = Url::to(['/baak/kegiatan-pengabdian-masyarakat/kegiatan-pengabdian-masyarakat-view-kprodi-frk/', 'id' => $model->kegiatan_pengabdian_masyarakat_id]);
                                                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => 'view']);
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
//                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                    'class' => 'kartik\grid\ExpandRowColumn',
                                    'value' => function($model, $key, $index, $column) {
                                        return GridView::ROW_COLLAPSED;
                                    },
                                    'detail' => function($model, $key, $index, $column) {
                                        $searchModelDokumenBuktiJabatan = backend\modules\baak\models\DokumenBuktiDosenJabatan::find()->where(['dosen_jabatan_id'=>$model->dosen_jabatan_id]);
                                        $dataProviderDokumen = new ActiveDataProvider([
                                            'query' => $searchModelDokumenBuktiJabatan,
                                        ]);

                                        return Yii::$app->controller->renderPartial('dokumen_bukti_jabatan', [
                                            'dataProviderDokumen' => $dataProviderDokumen,
                                            'model' => $model,
                                        ]);
                                    },

                                ],
                            [
                                'attribute' => 'struktur_jabatan_id',
                                'format' => 'raw',
                                'value'=>function($model) {return $model->strukturJabatan->jabatan;},
                                'label' => 'Nama Jabatan'
                            ],
                            
                            [
                                'label' => 'Jumlah SKS Dosen',
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
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{view}',
                                        'buttons' => [
                                            'view' => function ($url, $model) {
                                                $url = Url::to(['/baak/dosen-jabatan/dosen-jabatan-view-kprodi-frk/', 'id' => $model->dosen_jabatan_id]);
                                                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => 'view']);
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
     <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Kesimpulan</h3>

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
                            <span class="description-text">TOTAL SKS FRK</span>
                            <h5 class="description-header"><?=$total_sks_frk?> sks</h5>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <div class="col-sm-4 col-xs-6">
                        <div class="description-block border-right">
                            <span class="description-text">TOTAL SKS FED</span>
                            <h5 class="description-header"><?=$total_sks_fed?> sks</h5>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <div class="col-sm-4 col-xs-6">
                        <div class="description-block border-right">
                            <h5 class="description-header">
                                <?= Html::a('<i class="glyphicon glyphicon-hand-up"></i> Download Report', ['/baak/dosen/download-report-dosen', 'id'=>$model->dosen_id, 'tahun_ajaran_id'=>$tahun_ajaran_id, 'semester_id'=>$semester_id], [
                                    'class'=>'btn btn-primary', 
                                    'target'=>'_blank', 
                                    'data-toggle'=>'tooltip', 
                                    'title'=>'Will open the generated PDF file in a new window'
                                ]); ?>
                            </h5>
                        </div>
                        <!-- /.description-block -->
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.box-footer -->
        </div>
   
</div>

