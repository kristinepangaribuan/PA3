<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use kartik\tabs\TabsX;
/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\DosenMatakuliah */

$this->title = 'Mengajar Matakuliah';
$this->params['breadcrumbs'][] = ['label' => 'Frk', 'url' => ['dosen/frk']];
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
<div class="dosen-matakuliah-view">
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
            [
                'attribute' => 'kuliah_id',
                'format' => 'raw',
                'value' => $model->kuliah->nama_kul_ind,
            ],
            [
                'attribute' => 'jlh_tatap_muka_dosen',
                'format' => 'raw',
                'value' => $model->jlh_tatap_muka_dosen ." x Pertemuan",
            ],
            [
                'attribute' => 'jlh_sks_beban_kerja_dosen',
                'format' => 'raw',
                'value' => $model->jlh_sks_beban_kerja_dosen ." sks",
            ],
            [
                'label' => 'Tahun Ajaran',
                'format' => 'raw',
                'value' => $model->semester->tahunAjaran->tahun_ajaran,
            ],
            [
                'attribute' => 'Semester',
                'format' => 'raw',
                'value' => $model->semester->semester ,
            ],
            
//            'header_dokumen_bukti_id',
            [
                'attribute' => 'Jumlah Mahasiswa yang diajari',
                'format' => 'raw',
                'value' => $model->jlh_mhs_matakuliah ." Orang",
            ],
//            'jlh_mhs_matakuliah',
        ],
    ]) ?>
    </div>
                        </div>
                    </div>
    </div>
    
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
                        'label' => 'Aksi',
                        'format' => 'raw',
                        'value' => function ($data)use($model) {
                            $dokumen = \backend\modules\baak\models\DokumenBuktiDosenMatakuliah::find()
                                            ->where(['dosen_matakuliah_id' => $model->dosen_matakuliah_id])
                                            ->andWhere(['header_detail_dokumen_bukti_id' => $data['header_detail_dokumen_bukti_id']])->one();
                            if ($dokumen['nama_file'] != NULL) {
                                return Html::a($dokumen['nama_file'], ['dosen-matakuliah-download','id'=>$dokumen['dokumen_bukti_dosen_matakuliah_id']], ['class' => '']);
                            }else{
                                return 'Dokumen Belum Diupload';
                            }
                        },
                            ],
                        ],
                    ]),
                    'active' => true,
                                
                ],
                [
                  'label' => '<i class="fa fa-file"></i> Kelas Yang Diajari',
                   'content' => GridView::widget([
                    'dataProvider' => $dataProviderKelasPengajaran,
//        'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
//                    'nama_header_detail_dokumen_bukti',
                    [
                        'attribute' => 'kelas_id',
                        'label' => 'Nama Kelas',
                        'format' => 'raw',
                        'value' => function($model){
                            return $model->kelas->nama;
                        }
                    ],
                        ],
                    ]),
                                
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