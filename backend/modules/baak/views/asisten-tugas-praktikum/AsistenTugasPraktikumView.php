<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use kartik\tabs\TabsX;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\AsistenTugasPraktikum */

$this->title = 'Asistensi Tugas Praktikum';
$this->params['breadcrumbs'][] = ['label' => 'Form Rencana Kerja', 'url' => ['dosen/frk']];
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
    <?php if($model->status == 'Rencana Kerja'){ ?>
    <p>
        <?= Html::a('Perbaharui', ['asisten-tugas-praktikum-edit', 'id' => $model->asisten_tugas_praktikum_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Hapus', ['asisten-tugas-praktikum-del', 'id' => $model->asisten_tugas_praktikum_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Apakah Anda yakin ingin menghapus FRK Asistensi Tugas Praktikum Anda?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php } ?>

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
                'value' => $model->jlh_sks_asistensi,
            ],
//            'jlh_sks_asistensi',
//            'jlh_mhs_praktikum',
            [
                'attribute' => 'Jumlah Mahasiswa yang Diajar',
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
            //'status',
        ],
    ]) ?>
      </div>
                        </div>
                    </div>
                </div>
    
    
    
    <?php if($model->status == 'Approve FRK'){ ?>
    <div class="panel box box-primary">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" class="collapsed">
                            Form Realisasi FRK Dosen
                        </a>
                    </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse">
                    <div class="box-body">
                        <div class="col-md-12">
                            <?php \yii\widgets\Pjax::begin(); ?>
                                <?=Html::beginForm(['process-realisasi-frk', 'id'=>$model->asisten_tugas_praktikum_id],'post');?>
                                <div class="col-md-6"><?=Html::dropDownList('action','',['1'=>'Terealisasi','0'=>'Tidak Terealisasi'],['prompt'=>'Pilih Realisasi...','class'=>'form-control',])?></div>
                                <div class="col-md-2"><?=Html::submitButton('Kirim', ['class' => 'btn btn-primary',]);?></div>
                                <?=  Html::endForm()?>
                            <?php  yii\widgets\Pjax::end()?>
                                <div class="col-md-4">
                                    <?php if($model->status_realisasi==0){
                                        echo '<b>Status FRK Tidak Terealisasi</b>';
                                    }else{
                                        echo '<b>Status FRK Sudah Terealisasi</b>';
                                    }?>
                                </div>
                        </div>
                        </div>
                    </div>
                </div>
    <?php } ?>
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
                        'label' => 'File',
                        'format' => 'raw',
                        'value' => function ($data)use($model) {
                            $dokumen = \backend\modules\baak\models\DokumenBuktiAsisten::find()
                                            ->where(['asisten_tugas_praktikum_id' => $model->asisten_tugas_praktikum_id])
                                            ->andWhere(['header_detail_dokumen_bukti_id' => $data['header_detail_dokumen_bukti_id']])->one();
                            if ($dokumen['nama_file'] != NULL) {
                                if($model->status == 'Rencana Kerja' || $model->status == 'Approve FRK'){
                                    return Html::a($dokumen['nama_file'], ['asisten-tugas-praktikum-download','id'=>$dokumen['dokumen_bukti_asisten_id']], ['class' => ''])
                                .   '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                                        .Html::a('Perbaharui Dokumen', ['asisten-tugas-praktikum-edit-dokumen','id'=>$dokumen['asisten_tugas_praktikum_id'], 'id_dokumen'=>$data['header_detail_dokumen_bukti_id']], ['class' => 'btn btn-primary']);
                                }else{
                                    return Html::a($dokumen['nama_file'], ['asisten-tugas-praktikum-download','id'=>$dokumen['dokumen_bukti_asisten_id']], ['class' => '']);
                                }
                            } else {
                                if($model->status == 'Rencana Kerja' || $model->status == 'Approve FRK'){  
                                    return Html::a('Unggah Dokumen', ['asisten-tugas-praktikum-upload', 'id'=>$model->asisten_tugas_praktikum_id, 'id_dokumen'=>$data['header_detail_dokumen_bukti_id']], ['class'=>'btn btn-success']);
                                }else{
                                    return 'Dokumen Belum Diunggah';
                                }
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