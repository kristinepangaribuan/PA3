<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\baak\models\search\DosenMatakuliahSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dosen Matakuliahs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dosen-matakuliah-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Dosen Matakuliah', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'dosen_matakuliah_id',
            'dosen_id',
            'kuliah_id',
            'jlh_tatap_muka_dosen',
            'jlh_sks_beban_kerja_dosen',
            // 'semester_id',
            // 'header_dokumen_bukti_id',
            // 'jlh_mhs_matakuliah',
            // 'deleted',
            // 'created_at',
            // 'updated_at',
            // 'created_by',
            // 'updated_by',
            // 'deleted_at',
            // 'deleted_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
