<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\baak\models\search\PenugasanAsesorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Penugasan Asesors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="penugasan-asesor-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?= Html::a('Tambah Penugasan Asesor', ['penugasan-asesor-add'], ['class' => 'btn btn-success']) ?>
    </p>
    <h3><?= Html::encode('Asesor Pada Tahun Ajaran :'.$semester->tahunAjaran->tahun_ajaran .' Semester '.$semester->semester) ?></h3>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'dosen_id',
                'format' => 'raw',
                'value' => function ($model){
                    return $model->dosen->nama_dosen;
                }
            ],
            [
                'attribute' => 'semester_id',
                'format' => 'raw',
                'value' => function ($model){
                    return $model->semester->semester;
                }
            ],
            [
                'label' => 'Action',
                'format' => 'raw',
                'value' => function ($model){
                    return Html::a('View',['penugasan-asesor-view', 'id'=>$model->penugasan_asesor_id], ['class'=>'btn btn-primary'])
                         .'   '   
                        .Html::a('Hapus', ['penugasan-asesor-del', 'id' => $model->penugasan_asesor_id], [
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
