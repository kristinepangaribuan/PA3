<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\baak\models\DosenJabatanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dosen Jabatans';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dosen-jabatan-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Dosen Jabatan', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'dosen_jabatan_id',
            'dosen_id',
            'struktur_jabatan_id',
            'semester_id',
            'header_dokumen_bukti_id',
            // 'jlh_sks_beban_kerja_dosen',
            // 'deleted',
            // 'deleted_at',
            // 'deleted_by',
            // 'updated_at',
            // 'updated_by',
            // 'created_at',
            // 'created_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
