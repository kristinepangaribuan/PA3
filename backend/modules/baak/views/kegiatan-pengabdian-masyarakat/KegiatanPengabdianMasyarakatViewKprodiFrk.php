<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use kartik\tabs\TabsX;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\KegiatanPengabdianMasyarakat */

$this->title = 'Kegiatan Pengabdian Masyarakat';
$this->params['breadcrumbs'][] = ['label' => 'Frk', 'url' => ['dosen/frk']];
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
<div class="kegiatan-pengabdian-masyarakat-view">
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
//            'kegiatan_pengabdian_masyarakat_id',
            'nama_kegiatan',
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
            'kategori_kegiatan',
        ],
    ]) ?>
    </div>
                        </div>
                    </div>
                </div>

    <?php
        $items = [
            [
                'label' => '<i class="glyphicon glyphicon-home"></i> Dokumen Bukti',
                'content' => GridView::widget([
                    'dataProvider' => $dataProviderDokumenBukti,
    //        'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'nama_header_detail_dokumen_bukti',
                        [
                            'attribute' => 'Action',
                            'format' => 'raw',
                            'value' => function ($data)use($model) {
                                $dokumen = \backend\modules\baak\models\DokumenBuktiKegiatanPengabdian::find()
                                                ->where(['kegiatan_pengabdian_masyarakat_id' => $model->kegiatan_pengabdian_masyarakat_id])
                                                ->andWhere(['header_detail_dokumen_bukti_id' => $data['header_detail_dokumen_bukti_id']])->one();
                                if ($dokumen['nama_file'] != NULL) {
                                    return Html::a($dokumen['nama_file'], ['kegiatan-pengabdian-masyarakat-download','id'=>$dokumen['dokumen_bukti_kegiatan_pengabdian_id']], ['class' => '']);
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
                        'dataProvider' => $dataProviderkegiatanPengabdian,
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
                            'jabatan_dlm_kegiatan',
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
                ?></div>
                </div>
            </div>
        </div>
</div>
