<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\tabs\TabsX;
use yii\grid\GridView;
use kartik\file\FileInput;
use yii\helpers\Url;
use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\Penelitian */

$this->title = 'Penelitian';
$this->params['breadcrumbs'][] = ['label' => 'Form Rencana Kerja', 'url' => ['dosen/frk']];
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
<div class="penelitian-view">
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
    <?php if($modelPenelitian->status == 'Rencana Kerja'){ ?>
    <p>
        <?= Html::a('Perbaharui', ['penelitian-edit', 'id' => $modelPenelitian->penelitian_id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Hapus', ['penelitian-del', 'id' => $modelPenelitian->penelitian_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Apakah Anda yakin ingin menghapus FRK Penelitian Anda?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>
    <?php } ?>
    <?=
    DetailView::widget([
        'model' => $modelPenelitian,
        'attributes' => [
//            'penelitian_id',
//            'jenis_penelitian_id',
            [
                'attribute'=>'jenis_penelitian_id',
                'format' => 'raw',
                'value' => function($model){
                    $parent = backend\modules\baak\models\TahapanPenelitian::find()->where(['tahapan_penelitian_id'=>$model->tahapanPenelitian->is_parent_of])->one();
                    return $parent->tahapan_penelitian;
                },
            ],
            'judul_penelitian',
            [
                'attribute' => 'Tahun Ajaran',
                'format' => 'raw',
                'value' =>$modelPenelitian->semester->tahunAjaran->tahun_ajaran,
            ],
            [
                'attribute' => 'semester_id',
                'format' => 'raw',
                'label' => 'Semester',
                'value' =>$modelPenelitian->semester->semester,
            ],
            
//            'header_dokumen_bukti_id',
            [
                'attribute' => 'tahapan_penelitian_id',
                'format' => 'raw',
                'value' =>$modelPenelitian->tahapanPenelitian->tahapan_penelitian,
            ],
            [
                'attribute' => 'Jumlah target',
                'format' => 'raw',
                'value' => function ($data) {
                $sum = $data['jlh_target'];
                    return $data['jlh_target'] . '%';
                }
            ],
        ],
    ])
    ?>
    </div>
                        </div>
                    </div>
                </div>
    
    <?php if($modelPenelitian->status == 'Approve FRK'){ ?>
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
            <?=Html::beginForm(['process-realisasi-frk', 'id'=>$modelPenelitian->penelitian_id],'post');?>
            <div class="col-md-6"><?=Html::dropDownList('action','',['1'=>'Terealisasi','0'=>'Tidak Terealisasi'],['prompt'=>'Pilih Realisasi...','class'=>'form-control',])?></div>
            <div class="col-md-2"><?=Html::submitButton('Kirim', ['class' => 'btn btn-primary',]);?></div>
            <?=  Html::endForm()?>
        <?php  yii\widgets\Pjax::end()?>
            <div class="col-md-4">
                <?php if($modelPenelitian->status_realisasi==0){
                    echo '<b>Status Frk Tidak Terealisasi</b>';
                }else{
                    echo '<b>Status Frk Sudah Terealisasi</b>';
                }?></div>
                        </div>
                    </div>
                </div>
            </div>
    <?php } ?>
    
    <?php
    $items = [
        [
            'label' => '<i class="glyphicon glyphicon-file"></i> Dokumen Bukti',
            'content' => GridView::widget([
                'dataProvider' => $dataProviderHeaderDetailDokumenBukti,
//        'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'nama_header_detail_dokumen_bukti',
                        'label' => 'Nama Dokumen Bukti',
                        'format' => 'raw',
                        'value' => function($model){
                            return $model->nama_header_detail_dokumen_bukti;
                        }
                    ],
                    [
                        'attribute' => 'File',
                        'format' => 'raw',
                        'value' => function ($data)use($modelPenelitian) {
                            $dokumen = \backend\modules\baak\models\DokumenBuktiPenelitian::find()
                                            ->where(['penelitian_id' => $modelPenelitian->penelitian_id])
                                            ->andWhere(['header_detail_dokumen_bukti_id' => $data['header_detail_dokumen_bukti_id']])->one();
                            if ($dokumen['nama_file'] != NULL) {
                                if($modelPenelitian->status == 'Rencana Kerja' || $modelPenelitian->status == 'Approve FRK'){
                                    return Html::a($dokumen['nama_file'], ['penelitian-download','id'=>$dokumen['dokumen_bukti_penelitian_id']], ['class' => ''])
                                        .'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                                        .Html::a('Perbaharui Dokumen', ['penelitian-edit-dokumen','id'=>$dokumen['penelitian_id'], 'id_dokumen'=>$data['header_detail_dokumen_bukti_id']], ['class' => 'btn btn-primary']);
                                }else{
                                    return Html::a($dokumen['nama_file'], ['penelitian-download','id'=>$dokumen['dokumen_bukti_penelitian_id']], ['class' => '']);
                                }
                            } else {
                                if($modelPenelitian->status == 'Rencana Kerja' || $modelPenelitian->status == 'Approve FRK'){
                                    return Html::a('Unggah Dokumen', ['penelitian-upload', 'id'=>$modelPenelitian->penelitian_id, 'id_dokumen'=>$data['header_detail_dokumen_bukti_id']], ['class'=>'btn btn-success']);
                                }else{
                                    return 'Dokumen Belum Diunggah';
                                }
                            }
                        },
                            ],
                        ],
                    ]),
                    'active' => true
                ],
                                [
                    'label' => '<i class="glyphicon glyphicon-user"></i> Tim Dosen',
                    'content' => GridView::widget([
                        'dataProvider' => $dataProviderDosenPenelitian,
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
                            'jabatan_dlm_penelitian',
                            [
                                'attribute' => 'jlh_sks_beban_kerja_dosen',
                                'format' => 'raw',
                                'value' => function ($data)use($modelPenelitian) {
                                    $dosenPenelitian = backend\modules\baak\models\DosenPenelitian::find()->where(['penelitian_id' => $modelPenelitian->penelitian_id])->andWhere(['dosen_id' => $data->dosen_id])->one();
                                    return $dosenPenelitian->jlh_sks_beban_kerja_dosen;
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
