<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use kartik\tabs\TabsX;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\BimbinganKuliah */

$this->title = 'Bimbingan Kuliah';
$this->params['breadcrumbs'][] = ['label' => 'Frk', 'url' => ['dosen/frk']];
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
    
<?php
    $items = [
        [
            'label' => '<i class="glyphicon glyphicon-home"></i> Dokumen Bukti',
            'content' => GridView::widget([
                'dataProvider' => $dataProviderDokumenBukti,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'nama_header_detail_dokumen_bukti',
                    [
                        'attribute' => 'Action',
                        'format' => 'raw',
                        'value' => function ($data)use($model) {
                            $dokumen = \backend\modules\baak\models\DokumenBuktiBimbinganKuliah::find()
                                            ->where(['bimbingan_kuliah_id' => $model->bimbingan_kuliah_id])
                                            ->andWhere(['header_detail_dokumen_bukti_id' => $data['header_detail_dokumen_bukti_id']])->one();
                            if ($dokumen['nama_file'] != NULL) {
                                    return Html::a($dokumen['nama_file'], ['bimbingan-kuliah-download','id'=>$dokumen['dokumen_bukti_bimbingan_kuliah_id']], ['class' => '']);
                                }else{
                                    return 'Dokumen Belum Diupload';
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
