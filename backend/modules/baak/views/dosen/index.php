<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\baak\models\search\DosenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Laporan Dosen';
$this->params['breadcrumbs'][] = $this->title;
$status = 'Approve FED';
?>
<br>
<div class="dosen-index">
    <div class="row">
    <div class="col-md-12">
          <div class="box box-solid">
            <div class="box-header with-border">
                <div class="col-md-10">
                    <h1 class="box-title"><?= Html::encode($this->title) ?></h1>    
                </div>
                <div class="col-md-1">
                    <?= Html::a('<i class="glyphicon glyphicon-hand-up"></i> Download', ['/baak/dosen/download-report'], [
                                          'class'=>'btn btn-danger', 
                                          'target'=>'_blank', 
                                          'data-toggle'=>'tooltip', 
                                          'title'=>'Will open the generated PDF file in a new window'
                                      ]); ?>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="col-md-6">
                    <?php  echo $this->render('_search', ['model' => $searchModel, 'Prodi'=>$Prodi]); ?>
                </div>
                <div class="col-md-6">
                    </div>
            
            <!-- /.box-body -->
<div class="col-md-12">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'nama_dosen',
            'nidn',
            [
                'label' => 'FRK (SKS)',
                'format' => 'raw',
                'value' => function ($model) use ($semester, $status){
                    $terealisasi = \backend\modules\baak\models\Dosen::getAllFrkFed($status, $model->dosen_id, 1, $semester->semester_id);
                    $tidak_terealisasi = \backend\modules\baak\models\Dosen::getAllFrkFed($status, $model->dosen_id, 0, $semester->semester_id);
                    return  ($terealisasi + $tidak_terealisasi);
                }
            ],
            [
                'label' => 'FED (SKS)',
                'format' => 'raw',
                'value' => function ($model) use ($semester, $status){
                    return \backend\modules\baak\models\Dosen::getAllFrkFed($status, $model->dosen_id, 1, $semester->semester_id);
                }
            ],    
            // 'golongan_kepangkatan_id',
            // 'pegawai_id',
            // 'status_ikatan_kerja',
            // 'aktif_start',
            // 'aktif_end',
            // 'deleted',
            // 'created_at',
            // 'updated_at',
            // 'created_by',
            // 'updated_by',
            // 'deleted_at',
            // 'deleted_by',
            // 'user_id',
            // 'ref_kbk_id',
            [
                'label' => 'Rincian',
                'format' => 'raw',
                'value' => function($model) use ($semester){
                    return Html::a('<i class="glyphicon glyphicon-hand-up"></i> Lihat Rincian', ['/baak/dosen/report', 'id'=> $model->dosen_id, 'tahun_ajaran_id' => $semester->tahunAjaran->tahun_ajaran_id, 'semester_id'=>$semester->semester_id], [
                                    'class'=>'btn btn-primary', 
                                    'data-toggle'=>'tooltip', 
                                    'title'=>'View'
                                ]);
                }
            ],
//            [
//                'class' => 'yii\grid\ActionColumn',
//                'template' => '{view}',
//                'buttons' => [
//                    'view' => function ($url, $model)use ($semester) {
//                        $url = Url::to(['/baak/dosen/report/', 'id'=>$model->dosen_id, 'tahun_ajaran_id' => $semester->tahunAjaran->tahun_ajaran_id, 'semester_id'=>$semester->semester_id]);
//                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => 'view']);
//                    },
//                ],
//            ],
        ],
    ]); ?>
            </div></div>
    </div>
        </div>
    </div>
</div>
