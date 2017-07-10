<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\baak\models\models_search\PenelitianSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'FRK ' . $dosen['nama_dosen'] . ' Bidang Penelitian';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="penelitian-index">

    <h3><?= Html::encode($this->title) ?></h3>
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
                    <?php \yii\widgets\Pjax::begin(); ?>
                    <?=
                    GridView::widget([
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
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{view}',
                                        'buttons' => [
                                            'view' => function ($url, $model) {
                                                $url = Url::to(['/baak/penelitian/penelitian-view/', 'id' => $model->penelitian_id]);
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
                                                                    <h3 class="box-title"><?= Html::encode('Membuat Modul Bahan Ajar') ?></h3>

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
                                                                        'dataProvider' => $dataProviderModul,
                                                                        //        'filterModel' => $searchModel,
                                                                        'columns' => [
                                                                            ['class' => 'yii\grid\SerialColumn'],
                                                                            'nama_modul',
//                                                                            //'tahapan_modul_id',
                                                                            [
                                                                                'label' => 'Jumlah SKS',
                                                                                'format' => 'raw',
                                                                                'value' => function($model)use ($dosen) {
                                                                                    $dosenModulBahanAjar = backend\modules\baak\models\DosenModulBahanAjar::find()->where(['modul_bahan_ajar_id' => $model->modul_bahan_ajar_id])->andWhere(['dosen_id' => $dosen->dosen_id])->one();
                                                                                    return $dosenModulBahanAjar->jlh_sks_beban_kerja_dosen . "";
                                                                                }
                                                                                    ],
//                                                                                    [
//                                                                                        'attribute' => 'jlh_targer',
//                                                                                        'label' => 'Jumlah Target',
//                                                                                        'format' => 'raw',
//                                                                                        'value' => function ($model) {
//                                                                                            return $model->jlh_targer . "%";
//                                                                                        }
//                                                                                    ],
                                                                                    [
                                                                                        'class' => 'yii\grid\ActionColumn',
                                                                                        'template' => '{view}',
                                                                                        'buttons' => [
                                                                                            'view' => function ($url, $model) {
                                                                                                $url = Url::to(['/baak/modul-bahan-ajar/modul-bahan-ajar-view/', 'id' => $model->modul_bahan_ajar_id]);
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
                                                                                            <h3 class="box-title"><?= Html::encode('Menyajikan Malakah Seminar') ?></h3>

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
                                                                                                'dataProvider' => $dataProviderMakalahSeminar,
                                                                                                //        'filterModel' => $searchModel,
                                                                                                'columns' => [
                                                                                                    ['class' => 'yii\grid\SerialColumn'],
                                                                                                    'judul_makalah',
//                                                                                                    'tingkatan_makalah',
                                                                                                    [
                                                                                                        'label' => 'Jumlah SKS',
                                                                                                        'format' => 'raw',
                                                                                                        'value' => function($model)use ($dosen) {
                                                                                                            $dosenMakalahSeminar = backend\modules\baak\models\DosenMakalahSeminar::find()->where(['makalah_seminar_id' => $model->makalah_seminar_id])->andWhere(['dosen_id' => $dosen->dosen_id])->one();
                                                                                                            return $dosenMakalahSeminar->jlh_sks_beban_kerja_dosen . "";
                                                                                                        }
                                                                                                            ],
                                                                                                            [
                                                                                                                'class' => 'yii\grid\ActionColumn',
                                                                                                                'template' => '{view}',
                                                                                                                'buttons' => [
                                                                                                                    'view' => function ($url, $model) {
                                                                                                                        $url = Url::to(['/baak/makalah-seminar/makalah-seminar-view/', 'id' => $model->makalah_seminar_id]);
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
                                                                                    <h3 class="box-title"><?= Html::encode('Menulis Jurnal Ilmiah') ?></h3>

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
                                                                                        'dataProvider' => $dataProviderJurnalIlmiah,
                                                                                        'columns' => [
                                                                                            ['class' => 'yii\grid\SerialColumn'],
                                                                                            'judul_jurnal_ilmiah',
//                                                                                            'penerbit_jurnal_ilmiah',
                                                                                            [
                                                                                                'label' => 'Jumlah SKS',
                                                                                                'format' => 'raw',
                                                                                                'value' => function($model) use ($dosen) {
                                                                                                    $dosenJurnalIlmiah = backend\modules\baak\models\DosenJurnalIlmiah::find()->where(['jurnal_ilmiah_id' => $model->jurnal_ilmiah_id])->andWhere(['dosen_id' => $dosen->dosen_id])->one();
                                                                                                    return $dosenJurnalIlmiah->jlh_sks_beban_dosen . "";
                                                                                                }
                                                                                            ],
                                                                                            [
                                                                                                'class' => 'yii\grid\ActionColumn',
                                                                                                'template' => '{view}',
                                                                                                'buttons' => [
                                                                                                    'view' => function ($url, $model) {
                                                                                                        $url = Url::to(['/baak/jurnal-ilmiah/jurnal-ilmiah-view/', 'id' => $model->jurnal_ilmiah_id]);
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


