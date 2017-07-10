<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use kartik\tabs\TabsX;
/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\DosenMatakuliah */

$this->title = 'Mengajar Matakuliah';
$this->params['breadcrumbs'][] = ['label' => 'Form Rencana Kerja', 'url' => ['dosen/frk']];
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
<div class="dosen-matakuliah-view">
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
        <?= Html::a('Perbaharui', ['dosen-matakuliah-edit', 'id' => $model->dosen_matakuliah_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Hapus', ['dosen-matakuliah-del', 'id' => $model->dosen_matakuliah_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Apakah Anda yakin ingin menghapus FRK Matakuliah Anda?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php } ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'kuliah_id',
                'format' => 'raw',
                'value' => $model->kuliah->nama_kul_ind,
            ],
            [
                'attribute' => 'jlh_tatap_muka_dosen',
                'format' => 'raw',
                'value' => $model->jlh_tatap_muka_dosen ." x Pertemuan",
            ],
            [
                'attribute' => 'jlh_sks_beban_kerja_dosen',
                'format' => 'raw',
                'value' => $model->jlh_sks_beban_kerja_dosen ."",
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
            
//            'header_dokumen_bukti_id',
            [
                'attribute' => 'Jumlah Mahasiswa yang Diajar',
                'format' => 'raw',
                'value' => $model->jlh_mhs_matakuliah ." Orang",
            ],
//            'jlh_mhs_matakuliah',
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
            <?=Html::beginForm(['process-realisasi-frk', 'id'=>$model->dosen_matakuliah_id],'post');?>
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
                            $dokumen = \backend\modules\baak\models\DokumenBuktiDosenMatakuliah::find()
                                            ->where(['dosen_matakuliah_id' => $model->dosen_matakuliah_id])
                                            ->andWhere(['header_detail_dokumen_bukti_id' => $data['header_detail_dokumen_bukti_id']])->one();
                            if ($dokumen['nama_file'] != NULL) {
                                if($model->status == 'Rencana Kerja' || $model->status == 'Approve FRK'){
                                    return Html::a($dokumen['nama_file'], ['dosen-matakuliah-download','id'=>$dokumen['dokumen_bukti_dosen_matakuliah_id']], ['class' => ''])
                                        .'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                                        .Html::a('Perbaharui Dokumen', ['dosen-matakuliah-edit-dokumen','id'=>$dokumen['dosen_matakuliah_id'], 'id_dokumen'=>$data['header_detail_dokumen_bukti_id']], ['class' => 'btn btn-primary']);
                                }else{
                                    return Html::a($dokumen['nama_file'], ['dosen-matakuliah-download','id'=>$dokumen['dokumen_bukti_dosen_matakuliah_id']], ['class' => '']);
                                }
                            } else {
                                if($model->status == 'Rencana Kerja' || $model->status == 'Approve FRK'){
                                    return Html::a('Unggah Dokumen', ['dosen-matakuliah-upload', 'id'=>$model->dosen_matakuliah_id, 'id_dokumen'=>$data['header_detail_dokumen_bukti_id']], ['class'=>'btn btn-success']);
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
                  'label' => '<i class="fa fa-file"></i> Kelas Yang Diajar',
                   'content' => GridView::widget([
                    'dataProvider' => $dataProviderKelasPengajaran,
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