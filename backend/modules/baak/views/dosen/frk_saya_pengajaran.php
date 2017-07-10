<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\baak\models\models_search\PenelitianSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'FRK ' . $dosen['nama_dosen'] . ' Bidang Pengajaran';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="penelitian-index">

    <h3><?= Html::encode($this->title) ?></h3>
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
                    <?php \yii\widgets\Pjax::begin(); ?>
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
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{view}',
                                        'buttons' => [
                                            'view' => function ($url, $model) {
                                                $url = Url::to(['/baak/dosen-matakuliah/dosen-matakuliah-view/', 'id' => $model->dosen_matakuliah_id]);
                                                return Html::a('<i class="glyphicon glyphicon-hand-up"></i> Lihat Rincian', $url, ['title' => 'view']);
                                            },
                                                ]
                                            ],
                                        ],
                                    ]);
                                    ?>
                                    <?php \yii\widgets\Pjax::end(); ?>
                                    <!-- /.row -->
                                </div>
                                <!-- ./box-body -->
                            </div>
                            <!-- /.box -->
                        </div>
                        <!-- /.col -->
                    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Html::encode('Asistensi Tugas Praktikum') ?></h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <?php \yii\widgets\Pjax::begin(); ?>
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
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{view}',
                                        'buttons' => [
                                            'view' => function ($url, $model) {
                                                $url = Url::to(['/baak/asisten-tugas-praktikum/asisten-tugas-praktikum-view/', 'id' => $model->asisten_tugas_praktikum_id]);
                                                return Html::a('<i class="glyphicon glyphicon-hand-up"></i> Lihat Rincian', $url, ['title' => 'view']);
                                            },
                                                ]
                                            ],
                                        ],
                                    ]);
                                    ?>
                                    <?php \yii\widgets\Pjax::end(); ?>
                                    <!-- /.row -->
                                </div>
                                <!-- ./box-body -->
                            </div>
                            <!-- /.box -->
                        </div>
                        <!-- /.col -->
                    </div>
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
                    <?php \yii\widgets\Pjax::begin(); ?>
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProviderBimbinganKuliah,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'topik_bimbingan',
//                            'jlh_mhs_bimbingan_kuliah',
                            [
                                'label' => 'Jumlah SKS',
                                'format' => 'raw',
                                'value' => function($model)use ($dosen) {
//                                    $dosenPenelitian = backend\modules\baak\models\DosenPenelitian::find()->where(['penelitian_id' => $model->penelitian_id])->andWhere(['dosen_id' => $dosen->dosen_id])->one();
                                    return $model->jlh_sks_bimbingan_kuliah . "";
                                }
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{view}',
                                        'buttons' => [
                                            'view' => function ($url, $model) {
                                                $url = Url::to(['/baak/bimbingan-kuliah/bimbingan-kuliah-view/', 'id' => $model->bimbingan_kuliah_id]);
                                                return Html::a('<i class="glyphicon glyphicon-hand-up"></i> Lihat Rincian', $url, ['title' => 'view']);
                                            },
                                                ]
                                            ],
                                        ],
                                    ]);
                                    ?>
                                    <?php \yii\widgets\Pjax::end(); ?>
                                    <!-- /.row -->
                                </div>
                                <!-- ./box-body -->
                            </div>
                            <!-- /.box -->
                        </div>
                        <!-- /.col -->
                    </div>
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
                                    <?php \yii\widgets\Pjax::begin(); ?>
                                    <?=
                                    GridView::widget([
                                        'dataProvider' => $dataProviderSeminarTerjadwal,
                                        'columns' => [
                                            ['class' => 'yii\grid\SerialColumn'],
                                            'nama_seminar',
//                                            'jlh_mhs_seminar',
                                            [
                                                'label' => 'Jumlah SKS',
                                                'format' => 'raw',
                                                'value' => function($model)use ($dosen) {
                                                    $dosenSeminarTerjadwal= backend\modules\baak\models\DosenSeminarTerjadwal::find()->where(['seminar_terjadwal_id' => $model->seminar_terjadwal_id])->andWhere(['dosen_id' => $dosen->dosen_id])->one();
                                                    return $dosenSeminarTerjadwal->jlh_sks_beban_kerja_dosen . "";
                                                }
                                                    ],
                                                    [
                                                        'class' => 'yii\grid\ActionColumn',
                                                        'template' => '{view}',
                                                        'buttons' => [
                                                            'view' => function ($url, $model) {
                                                                $url = Url::to(['/baak/seminar-terjadwal/seminar-terjadwal-view/', 'id' => $model->seminar_terjadwal_id]);
                                                                return Html::a('<i class="glyphicon glyphicon-hand-up"></i> Lihat Rincian', $url, ['title' => 'view']);
                                                            },
                                                                ],
                                                            ],
                                                        ],
                                                    ]);
                                                    ?>
                                                    <?php \yii\widgets\Pjax::end(); ?>
                                                    <!-- /.row -->
                                                </div>
                                                <!-- ./box-body -->
                                            </div>
                                            <!-- /.box -->
                                        </div>
                                        <!-- /.col -->
                                    </div>
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
                                                    <?php \yii\widgets\Pjax::begin(); ?>
                                                    <?=
                                                    GridView::widget([
                                                        'dataProvider' => $dataProviderMengujiProposal,
                                                        'columns' => [
                                                            ['class' => 'yii\grid\SerialColumn'],
                                                            'judul_proposal',
//                                                            'jlh_mhs_proposal',
//                                                            'jlh_sks_menguji_proposal',
                                                            [
                                                                'label' => 'Jumlah SKS',
                                                                'format' => 'raw',
                                                                'value' => function($model)use ($dosen) {
//                                                                    $dosenMediaMassa = backend\modules\baak\models\MediaMassa::find()->where(['media_massa_id' => $model->media_massa_id])->andWhere(['dosen_id' => $dosen->dosen_id])->one();
                                                                    return $model->jlh_sks_menguji_proposal. "";
                                                                }
                                                                    ],
                                                                    [
                                                                        'class' => 'yii\grid\ActionColumn',
                                                                        'template' => '{view}',
                                                                        'buttons' => [
                                                                            'view' => function ($url, $model) {
                                                                                $url = Url::to(['/baak/menguji-proposal/menguji-proposal-view/', 'id' => $model->menguji_proposal_id]);
                                                                                return Html::a('<i class="glyphicon glyphicon-hand-up"></i> Lihat Rincian', $url, ['title' => 'view']);
                                                                            },
                                                                                ],
                                                                            ],
                                                                        ],
                                                                    ]);
                                                                    ?>
                                                                    <?php \yii\widgets\Pjax::end(); ?>
                                                                    <!-- /.row -->
                                                                </div>
                                                                <!-- ./box-body -->
                                                            </div>
                                                            <!-- /.box -->
                                                        </div>
                                                        <!-- /.col -->
                                                    </div>
                                                  
</div>

