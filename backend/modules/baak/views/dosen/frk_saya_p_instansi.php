<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\tabs\TabsX;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\baak\models\models_search\PenelitianSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'FRK ' . $dosen['nama_dosen'] . ' Bidang Pengembangan Instansi';
?>
<div class="penelitian-index">

    <h3><?= Html::encode($this->title) ?></h3>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Html::encode('Jabatan Dalam Instansi') ?></h3>
                    <div class="box-tools pull-right">
                        
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <?php \yii\widgets\Pjax::begin(); ?>
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProviderDosenJabatan,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            
                            [
                                'attribute' => 'struktur_jabatan_id',
                                'format' => 'raw',
                                'value'=>function($model) {return $model->strukturJabatan->jabatan;},
                                'label' => 'Nama Jabatan'
                            ],
                            [
                                'label' => 'Jumlah SKS',
                                'format' => 'raw',
                                'value' => function($model)use ($dosen) {
                                    return $model->jlh_sks_beban_kerja_dosen . "";
                                }
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{view}',
                                        'buttons' => [
                                            'view' => function ($url, $model) {
                                                $url = Url::to(['/baak/dosen-jabatan/dosen-jabatan-view/', 'id' => $model->dosen_jabatan_id]);
                                                return Html::a('<i class="glyphicon glyphicon-hand-up"></i> Lihat Rincian', $url, ['title' => 'view']);
                                            },
                                                ]
                                            ],
                                        ],
                                    ]);
                                    ?>
                                    <?php \yii\widgets\Pjax::end(); ?>
                                    <!-- /.row -->
                                </div>
            <!-- ./box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
</div>
