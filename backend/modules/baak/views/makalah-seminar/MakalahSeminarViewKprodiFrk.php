<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\HtmlPurifier;
use yii\grid\GridView;
use kartik\tabs\TabsX;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\MakalahSeminar */

$this->title = 'Makalah Seminar';
$this->params['breadcrumbs'][] = ['label' => 'Frk', 'url' => ['dosen/frk']];
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
<div class="makalah-seminar-view">
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
//            'makalah_seminar_id',
            'judul_makalah',
            'tingkatan_makalah',
            [
                'attribute' => 'tahun_ajaran',
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
            [
                'attribute' => 'header_dokumen_bukti_id',
                'format' => 'raw',
                'label' => 'Header Dokumen Bukti',
                'value' =>$model->headerDokumenBukti->nama_header_dokumen_bukti,
            ],
            
//            'deleted',
//            'created_at',
//            'updated_at',
//            'created_by',
//            'updated_by',
//            'deleted_at',
//            'deleted_by',
//            'status',
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
                            $dokumen = \backend\modules\baak\models\DokumenBuktiMakalahSeminar::find()
                                            ->where(['makalah_seminar_id' => $model->makalah_seminar_id])
                                            ->andWhere(['header_detail_dokumen_bukti_id' => $data['header_detail_dokumen_bukti_id']])->one();
                            if ($dokumen['nama_file'] != NULL) {
                                return Html::a($dokumen['nama_file'], ['makalah-seminar-download','id'=>$dokumen['dokumen_bukti_makalah_seminar_id']], ['class' => '']);
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
                    'label' => '<i class="glyphicon glyphicon-user"></i> Tim Dosen',
                    'content' => GridView::widget([
                        'dataProvider' => $dataProviderDosenMakalahSeminar,
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
                            'jabatan_dlm_makalah_seminar',
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