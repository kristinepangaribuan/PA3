<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\tabs\TabsX;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\baak\models\models_search\PenelitianSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'FRK ' . $dosen['nama_dosen'] . ' Bidang Pengabdian Masyarakat';
?>
<div class="penelitian-index">

    <h3><?= Html::encode($this->title) ?></h3>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Html::encode('Melakukan Pengabdian Masyarakat') ?></h3>
                    <div class="box-tools pull-right">
                        
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                 <div class="box-body">
                    <?php \yii\widgets\Pjax::begin(); ?>
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProviderKegiatanPengabdian,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'nama_kegiatan',
//                            'kategori_kegiatan',
                            [
                                'label' => 'Jumlah SKS',
                                'format' => 'raw',
                                'value' => function($model)use ($dosen) {
                                    $dosenKegiatan= backend\modules\baak\models\DosenKegiatanPengabdian::find()->where(['kegiatan_pengabdian_masyarakat_id' => $model->kegiatan_pengabdian_masyarakat_id])->andWhere(['dosen_id' => $dosen->dosen_id])->one();
                                    return $dosenKegiatan->jlh_sks_beban_kerja_dosen . "";
                                }
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{view}',
                                        'buttons' => [
                                            'view' => function ($url, $model) {
                                                $url = Url::to(['/baak/kegiatan-pengabdian-masyarakat/kegiatan-pengabdian-masyarakat-view/', 'id' => $model->kegiatan_pengabdian_masyarakat_id]);
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
