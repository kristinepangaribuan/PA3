<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use kartik\tabs\TabsX;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\ModulBahanAjar */

$this->title = 'Modul Bahan Ajar';
$this->params['breadcrumbs'][] = ['label' => 'Frk', 'url' => ['dosen/frk']];
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
<div class="modul-bahan-ajar-view">
    
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
//            'modul_bahan_ajar_id',
            'nama_modul',
            [
                'attribute'=>'tahun_ajaran',
                'label' => 'Tahun Ajaran',
                'format' => 'raw',
                'value' =>$model->semester->tahunAjaran->tahun_ajaran,
            ],
            [
                'attribute' => 'semester_id',
                'format' => 'raw',
                'label' => 'Semester',
                'value' =>$model->semester->semester,
            ],
//            'semester_id',
//            'header_dokumen_bukti_id',
            [
                'attribute'=>'tahapan_modul_id',
                'label' => 'Tahapan Modul',
                'format' => 'raw',
                'value' => $model->tahapanModul->tahapan_modul,
            ],
//            'jlh_targer',
            [
                'attribute'=>'jlh_targer',
                'label' => 'Jumlah target',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data['jlh_targer'] . '%';
                }
            ],
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
                            $dokumen = \backend\modules\baak\models\DokumenBuktiModul::find()
                                            ->where(['modul_bahan_ajar_id' => $model->modul_bahan_ajar_id])
                                            ->andWhere(['header_detail_dokumen_bukti_id' => $data['header_detail_dokumen_bukti_id']])->one();
                            if ($dokumen['nama_file'] != NULL) {
                                    return Html::a($dokumen['nama_file'], ['modul-bahan-ajar-download','id'=>$dokumen['dokumen_bukti_modul_id']], ['class' => '']);
                                }else{
                                    return 'Dokumen Belum Diupload';
                                }
                        }
                    ],
                        ],
                    ]),
                    'active' => true
                ],
                            [
                    'label' => '<i class="glyphicon glyphicon-user"></i> Tim Dosen',
                    'content' => GridView::widget([
                        'dataProvider' => $dataProvider,
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
                            'jabatan_dlm_modul_bahan_ajar',
                            [
                                'attribute' => 'jlh_sks_beban_kerja_dosen',
                                'format' => 'raw',
                                'value' => function ($data)use($model) {
                                    $dosenModul = backend\modules\baak\models\DosenModulBahanAjar::find()->where(['modul_bahan_ajar_id' => $model->modul_bahan_ajar_id])->andWhere(['dosen_id' => $data->dosen_id])->one();
                                    return $dosenModul->jlh_sks_beban_kerja_dosen;
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

