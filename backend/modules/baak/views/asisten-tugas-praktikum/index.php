<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\baak\models\search\AsistenTugasPraktikumSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Asisten Tugas Praktikums';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asisten-tugas-praktikum-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Asisten Tugas Praktikum', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'asisten_tugas_praktikum_id',
            'jlh_sks_asistensi',
            'jlh_mhs_praktikum',
            'kuliah_id',
            'semester_id',
            // 'header_dokumen_bukti_id',
            // 'deleted',
            // 'created_at',
            // 'updated_at',
            // 'created_by',
            // 'updated_by',
            // 'deleted_at',
            // 'deleted_by',
            // 'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
