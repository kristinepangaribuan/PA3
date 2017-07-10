<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use kartik\tabs\TabsX;
/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\DosenJabatan */

$this->title = $model->strukturJabatan->jabatan;
$this->params['breadcrumbs'][] = ['label' => 'Form Rencana Kerja', 'url' => ['dosen/frk']];
?>
<br>
<div class="dosen-jabatan-view">
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
    <?php if($model->status == 'Rencana Kerja' || $model->status == 'Approve FRK'){ ?>
    <p>
        <?= Html::a('Perbaharui', ['dosen-jabatan-edit', 'id' => $model->dosen_jabatan_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Hapus', ['dosen-jabatan-del', 'id' => $model->dosen_jabatan_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Apakah Anda yakin ingin menghapus FRK Jabatan Anda?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php } ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label'=>'Tahun Ajaran',
                'attribut'=>'tahun_ajaran',
                'value'=> $model->semester->tahunAjaran->tahun_ajaran,
            ],
            [
                'attribute'=>'semester_id',
                'label'=>'Semester',
                'value'=>$model->semester->semester,
            ],
            [
                'attribute'=> 'header_dokumen_bukti_id',
                'label'=>'Header Dokumen Bukti',
                'format'=>'raw',
                'value'=>$model->headerDokumenBukti->nama_header_dokumen_bukti,
            ],
             [
                'attribute' => 'struktur_jabatan_id',
                'format' => 'raw',
                'value'=>$model->strukturJabatan->jabatan,
                'label' => 'Nama Jabatan'
            ],
            'jlh_sks_beban_kerja_dosen',
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
            <?=Html::beginForm(['process-realisasi-frk', 'id'=>$model->dosen_jabatan_id],'post');?>
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
                            $dokumen = \backend\modules\baak\models\DokumenBuktiDosenJabatan::find()
                                            ->where(['dosen_jabatan_id' => $model->dosen_jabatan_id])
                                            ->andWhere(['header_detail_dokumen_bukti_id' => $data['header_detail_dokumen_bukti_id']])->one();
                            if ($dokumen['nama_file'] != NULL) {
                                if($model->status == 'Rencana Kerja' || $model->status == 'Approve FRK'){
                                    return Html::a($dokumen['nama_file'], ['dosen-jabatan-download','id'=>$dokumen['dokumen_bukti_dosen_jabatan_id']], ['class' => ''])
                                        .'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                                        .Html::a('Perbaharui Dokumen', ['dosen-jabatan-edit-dokumen','id'=>$dokumen['dosen_jabatan_id'], 'id_dokumen'=>$data['header_detail_dokumen_bukti_id']], ['class' => 'btn btn-primary']);
                                }else{
                                    return Html::a($dokumen['nama_file'], ['dosen-jabatan-download','id'=>$dokumen['dokumen_bukti_dosen_jabatan_id']], ['class' => '']);
                                }
                            } else {
                                if($model->status == 'Rencana Kerja' || $model->status == 'Approve FRK'){   
                                    return Html::a('Unggah Dokumen', ['dosen-jabatan-upload', 'id'=>$model->dosen_jabatan_id, 'id_dokumen'=>$data['header_detail_dokumen_bukti_id']], ['class'=>'btn btn-success']);
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
