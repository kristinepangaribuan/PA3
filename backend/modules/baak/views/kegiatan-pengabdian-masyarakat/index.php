<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\baak\models\search\KegiatanPengabdianMasyarakatSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kegiatan Pengabdian Masyarakats';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kegiatan-pengabdian-masyarakat-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Kegiatan Pengabdian Masyarakat', ['kegiatan-pengabdian-masyarakat-add'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'kegiatan_pengabdian_masyarakat_id',
            'nama_kegiatan',
            'semester_id',
            'header_dokumen_bukti_id',
            'kategori_kegiatan',
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
