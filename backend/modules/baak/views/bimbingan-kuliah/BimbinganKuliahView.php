<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use kartik\tabs\TabsX;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\BimbinganKuliah */

$this->title = 'Bimbingan Kuliah';
$this->params['breadcrumbs'][] = ['label' => 'Form Rencana Kerja', 'url' => ['dosen/frk']];
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
<div class="bimbingan-kuliah-view">
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
        <?= Html::a('Perbaharui', ['bimbingan-kuliah-edit', 'id' => $model->bimbingan_kuliah_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Hapus', ['bimbingan-kuliah-del', 'id' => $model->bimbingan_kuliah_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Apakah Anda yakin ingin menghapus FRK Bimbingan Kuliah Anda?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php } ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'bimbingan_kuliah_id',
            'topik_bimbingan',
            'jlh_mhs_bimbingan_kuliah',
            
            [
                'attribute'=>'jenis_bimbingan_id',
                'format' =>'raw',
                'value'=>$model->jenisBimbingan->jenis_bimbingan,
            ],
            [
                'attribute'=>'tahun_ajaran',
                'label'=>'Tahun Ajaran',
                'format' =>'raw',
                'value'=>$model->semester->tahunAjaran->tahun_ajaran,
            ],
            [
                'attribute'=>'semester_id',
                'format' =>'raw',
                'value'=>$model->semester->semester,
            ],
//            'dosen_id',
            'jlh_sks_bimbingan_kuliah',
//            'deleted',
//            'created_at',
//            'updated_at',
//            'created_by',
//            'updated_by',
//            'deleted_at',
//            'deleted_by',
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
                                <?=Html::beginForm(['process-realisasi-frk', 'id'=>$model->bimbingan_kuliah_id],'post');?>
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
            'label' => '<i class="glyphicon glyphicon-file"></i> Dokumen Bukti',
            'content' => GridView::widget([
                'dataProvider' => $dataProviderDokumenBukti,
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
                        'value' => function ($data)use($model) {
                            $dokumen = \backend\modules\baak\models\DokumenBuktiBimbinganKuliah::find()
                                            ->where(['bimbingan_kuliah_id' => $model->bimbingan_kuliah_id])
                                            ->andWhere(['header_detail_dokumen_bukti_id' => $data['header_detail_dokumen_bukti_id']])->one();
                            if ($dokumen['nama_file'] != NULL) {
                                if($model->status == 'Rencana Kerja' || $model->status == 'Approve FRK'){
                                    return Html::a($dokumen['nama_file'], ['bimbingan-kuliah-download','id'=>$dokumen['dokumen_bukti_bimbingan_kuliah_id']], ['class' => ''])
                                        .'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                                        .Html::a('Perbaharui Dokumen', ['bimbingan-kuliah-edit-dokumen','id'=>$dokumen['bimbingan_kuliah_id'], 'id_dokumen'=>$data['header_detail_dokumen_bukti_id']], ['class' => 'btn btn-primary']);
                                }else{
                                    return Html::a($dokumen['nama_file'], ['bimbingan-kuliah-download','id'=>$dokumen['dokumen_bukti_bimbingan_kuliah_id']], ['class' => '']);
                                }
                            } else {
                                if($model->status == 'Rencana Kerja' || $model->status == 'Approve FRK'){
                                    return Html::a('Unggah Dokumen', ['bimbingan-kuliah-upload', 'id'=>$model->bimbingan_kuliah_id, 'id_dokumen'=>$data['header_detail_dokumen_bukti_id']], ['class'=>'btn btn-success']);
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
