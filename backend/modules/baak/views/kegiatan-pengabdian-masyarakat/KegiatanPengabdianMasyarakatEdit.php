<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\KegiatanPengabdianMasyarakat */

$this->title = 'Edit Kegiatan Pengabdian Masyarakat: ' . $model->kegiatan_pengabdian_masyarakat_id;
$this->params['breadcrumbs'][] = ['label' => 'Frk', 'url' => ['dosen/frk']];
$this->params['breadcrumbs'][] = ['label' => $model->kegiatan_pengabdian_masyarakat_id, 'url' => ['kegiatan-pengabdian-masyarakat-view', 'id' => $model->kegiatan_pengabdian_masyarakat_id]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="kegiatan-pengabdian-masyarakat-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'dataDosen' => $dataDosen,
        'semester' => $semester,
        'resultDosen' => $resultDosen,
        'dosen'=>$dosen,
    ]) ?>

</div>
