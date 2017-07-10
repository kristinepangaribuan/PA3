<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\tabs\TabsX;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\JurnalIlmiah */

$this->title = 'Jurnal Ilmiah';
$this->params['breadcrumbs'][] = ['label' => 'Jurnal Ilmiahs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
<div class="jurnal-ilmiah-view">
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
//            'jurnal_ilmiah_id',
            'judul_jurnal_ilmiah',
            'penerbit_jurnal_ilmiah',
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
                'attribute'=> 'jlh_sks_jurnal',
                'label'=>'Jumlah Sks Jurnal',
                'format'=>'raw',
                'value'=>$model->jlh_sks_jurnal .' sks',
            ],
//            'semester_id',
//            'header_dokumen_bukti_id',
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
                            $dokumen = \backend\modules\baak\models\DokumenBuktiJurnalIlmiah::find()
                                            ->where(['jurnal_ilmiah_id' => $model->jurnal_ilmiah_id])
                                            ->andWhere(['header_detail_dokumen_bukti_id' => $data['header_detail_dokumen_bukti_id']])->one();
                            if ($dokumen['nama_file'] != NULL) {
                                return Html::a($dokumen['nama_file'], ['jurnal-ilmiah-download','id'=>$dokumen['dokumen_bukti_jurnal_ilmiah_id']], ['class' => '']);
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
                        'dataProvider' => $dataProviderJurnalIlmiah,
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
                            'jabatan_dlm_jurnal_ilmiah',
                            [
                                'attribute' => 'jlh_sks_beban_dosen',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return $model->jlh_sks_beban_dosen . ' SKS';
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