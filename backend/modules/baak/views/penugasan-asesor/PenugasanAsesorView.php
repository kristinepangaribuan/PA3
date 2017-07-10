<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\PenugasanAsesor */

$this->title = $model->penugasan_asesor_id;
$this->params['breadcrumbs'][] = ['label' => 'Penugasan Asesors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="penugasan-asesor-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'penugasan_asesor_id',
            [
                'attribute' => 'dosen_id',
                'label' => 'Nama Dosen',
                'format' => 'raw',
                'value' => $model->dosen->nama_dosen,
            ],
            [
                'label' => 'Tahun Ajaran',
                'format' => 'raw',
                'value' => $semester->tahunAjaran->tahun_ajaran,
            ],
            [
                'attribute' => 'semester_id',
                'label' => 'Semester',
                'format' => 'raw',
                'value' => $model->semester->semester,
            ],
        ],
    ]) ?>
    <p>
        <?= Html::a('Tambah Dosen', ['penugasan-asesor-dosen-asesor', 'id'=>$model->penugasan_asesor_id], ['class'=>'btn btn-primary']);?>
    </p>
    <h2><?= Html::encode('List Dosen yang diasesori') ?></h2>
    <?= GridView::widget([
        'dataProvider' => $dataProviderDosenAsesor,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'dosen_id',
                'format' => 'raw',
                'value' => function ($model){
                    return $model->dosen->nama_dosen;
                },
            ],
            [
                'label'=>'Action',
                'format'=>'raw',
                'value' => function($model){
                    return Html::a('Hapus', ['penugasan-asesor-dosen-asesor-del', 'id' => $model->dosen_asesor_id, 'id_penugasan_asesor'=>$model->penugasan_asesor_id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Apakah Anda yakin ingin menghapus Dosen ini?',
                            'method' => 'post',
                        ],
                    ]);
                }
            ],            
        ],
    ]); ?>

</div>
