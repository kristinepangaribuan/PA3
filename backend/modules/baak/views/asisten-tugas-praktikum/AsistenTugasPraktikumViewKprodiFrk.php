<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use kartik\tabs\TabsX;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\AsistenTugasPraktikum */

$this->title = 'Asisten Tugas Praktikum';
$this->params['breadcrumbs'][] = ['label' => 'Frk', 'url' => ['dosen/frk']];
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
<div class="asisten-tugas-praktikum-view">
    <div class="panel box box-primary">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse" aria-expanded="false" class="collapsed">
                             <?= Html::encode($this->title) ?>
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="box-body">
                        <div class="col-md-12">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'asisten_tugas_praktikum_id',
            [
                'attribute' => 'kuliah_id',
                'format' => 'raw',
                'value' => $model->kuliah->nama_kul_ind,
            ],
            [
                'attribute' => 'jlh_sks_asistensi',
                'format' => 'raw',
                'value' => $model->jlh_sks_asistensi ." SKS",
            ],
//            'jlh_sks_asistensi',
//            'jlh_mhs_praktikum',
            [
                'attribute' => 'Jumlah Mahasiswa yang diajari',
                'format' => 'raw',
                'value' => $model->jlh_mhs_praktikum ." Orang",
            ],
            [
                'label' => 'Tahun Ajaran',
                'format' => 'raw',
                'value' => $model->semester->tahunAjaran->tahun_ajaran,
            ],
            [
                'attribute' => 'Semester',
                'format' => 'raw',
                'value' => $model->semester->semester ,
            ],
//            'kuliah_id',
//            'semester_id',
//            'header_dokumen_bukti_id',
            'status',
        ],
    ]) ?>
      </div>
                        </div>
                    </div>
                </div>
    
    
<?php
    $items = [
        [
            'label' => '<i class="fa fa-file"></i> Dokumen Bukti',
            'content' => GridView::widget([
                'dataProvider' => $dataProviderHeaderDetailDokumenBukti,
//        'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
//                    'nama_header_detail_dokumen_bukti',
                    [
                        'attribute' => 'nama_header_detail_dokumen_bukti',
                        'label' => 'Nama Dokumen Bukti',
                        'format' => 'raw',
                        'value' => function($model){
                            return $model->nama_header_detail_dokumen_bukti;
                        }
                    ],
                    [
                        'attribute' => 'nama_header_detail_dokumen_bukti',
                        'label' => 'Aksi',
                        'format' => 'raw',
                        'value' => function ($data)use($model) {
                            $dokumen = \backend\modules\baak\models\DokumenBuktiAsisten::find()
                                            ->where(['asisten_tugas_praktikum_id' => $model->asisten_tugas_praktikum_id])
                                            ->andWhere(['header_detail_dokumen_bukti_id' => $data['header_detail_dokumen_bukti_id']])->one();
                            if ($dokumen['nama_file'] != NULL) {
                                    return Html::a($dokumen['nama_file'], ['asisten-tugas-praktikum-download','id'=>$dokumen['dokumen_bukti_asisten_id']], ['class' => '']);
                                }else{
                                    return 'Dokumen Belum Diupload';
                                }
                            
                        },
                            ],
                        ],
                    ]),
                    'active' => true,
                                
                ],
                [
                  'label' => '<i class="fa fa-file"></i> Kelas Praktikum',
                   'content' => GridView::widget([
                    'dataProvider' => $dataProviderKelasPraktikum,
//        'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
//                    'nama_header_detail_dokumen_bukti',
                    [
                        'attribute' => 'kelas_id',
                        'label' => 'Nama Kelas',
                        'format' => 'raw',
                        'value' => function($model){
                            return $model->kelas->nama;
                        }
                    ],
                        ],
                    ]),
                                
                ],
                            [
                    'label' => '<i class="glyphicon glyphicon-user"></i> Tim Dosen',
                    'content' => GridView::widget([
                        'dataProvider' => $dataProviderDosen,
//        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'dosen_id',
                                'label'=>'Nama Dosen',
                                'format' => 'raw',
                                'value' => function ($model){
                                    return $model->dosen->nama_dosen;
                                }
                            ],
                            [
                                'attribute' => 'jlh_sks_beban_kerja_dosen',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return $model->jlh_sks_beban_kerja_dosen . ' sks';
                                }
                            ],
                        //'jlh_sks_beban_kerja_dosen',
                        ],
                    ]),
//            'linkOptions'=>['data-url'=>\yii\helpers\Url::to(['/site/tabs-data'])]
                ],
            ];
            ?>
        <div class="panel box box-primary">
                <div id="collapse3" class="panel-collapse collapse in">
                    <div class="box-body">
                        <div class="col-md-12">
            <?=
            TabsX::widget([
                'items' => $items,
                'position' => TabsX::POS_ABOVE,
                'align' => TabsX::ALIGN_LEFT,
                'bordered' => true,
                'encodeLabels' => false
            ]);
            ?>
                            </div>
                        </div>
                    </div>
                </div>

</div>