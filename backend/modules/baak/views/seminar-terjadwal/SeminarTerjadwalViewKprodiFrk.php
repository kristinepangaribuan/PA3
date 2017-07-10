<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use kartik\tabs\TabsX;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\SeminarTerjadwal */

$this->title = 'Seminar Terjadwal';
$this->params['breadcrumbs'][] = ['label' => 'frk', 'url' => ['dosen/frk']];
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
<div class="seminar-terjadwal-view">
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
//            'seminar_terjadwal_id',
            'nama_seminar',
            'jlh_mhs_seminar',
//            'semester_id',
//            'header_dokumen_bukti_id',
            [
                'attribute'=>'header_dokumen_bukti_id',
                'format' =>'raw',
                'value'=>$model->headerDokumenBukti->nama_header_dokumen_bukti,
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
//            [
//                'label'=>'Jumlah Sks Seminar',
//                'format' =>'raw',
//                'value'=>$model->getSksSeminar($model->seminar_terjadwal_id, $model->),
//            ],
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
                'dataProvider' => $dataProviderHeaderDetailDokumenBukti,
//        'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'nama_header_detail_dokumen_bukti',
                    [
                        'attribute' => 'Action',
                        'format' => 'raw',
                        'value' => function ($data)use($model) {
                            $dokumen = \backend\modules\baak\models\DokumenBuktiSeminarTerjadwal::find()
                                            ->where(['seminar_terjadwal_id' => $model->seminar_terjadwal_id])
                                            ->andWhere(['header_detail_dokumen_bukti_id' => $data['header_detail_dokumen_bukti_id']])->one();
                            if ($dokumen['nama_file'] != NULL) {
                                    return Html::a($dokumen['nama_file'], ['seminar-terjadwal-download','id'=>$dokumen['dokumen_bukti_seminar_terjadwal_id']], ['class' => '']);
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
                        'dataProvider' => $dataProviderDokumenBukti,
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