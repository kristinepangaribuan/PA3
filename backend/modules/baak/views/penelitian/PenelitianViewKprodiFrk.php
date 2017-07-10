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
$this->params['breadcrumbs'][] = ['label' => 'Frk', 'url' => ['dosen/frk']];
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
    <?=
    DetailView::widget([
        'model' => $modelPenelitian,
        'attributes' => [
//            'penelitian_id',
            'jenis_penelitian_id',
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
    
    
    <?php
    $items = [
        [
            'label' => '<i class="glyphicon glyphicon-home"></i> Dokumen Bukti',
            'content' => GridView::widget([
                'dataProvider' => $dataProviderHeaderDetailDokumenBukti,
//        'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'nama_header_detail_dokumen_bukti',
                    [
                        'attribute' => 'Action',
                        'format' => 'raw',
                        'value' => function ($data)use($modelPenelitian) {
                            $dokumen = \backend\modules\baak\models\DokumenBuktiPenelitian::find()
                                            ->where(['penelitian_id' => $modelPenelitian->penelitian_id])
                                            ->andWhere(['header_detail_dokumen_bukti_id' => $data['header_detail_dokumen_bukti_id']])->one();
                            if ($dokumen['nama_file'] != NULL) {
                                    return Html::a($dokumen['nama_file'], ['penelitian-download','id'=>$dokumen['dokumen_bukti_penelitian_id']], ['class' => '']);
                                }else{
                                    return 'Dokumen Belum Diupload';
                                }
                        },
                            ],
                        ],
                    ]),
                    'active' => true
                ],
                                [
                    'label' => '<i class="glyphicon glyphicon-user"></i> Group Member',
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
//                            'jabatan_dlm_naskah_buku',
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
