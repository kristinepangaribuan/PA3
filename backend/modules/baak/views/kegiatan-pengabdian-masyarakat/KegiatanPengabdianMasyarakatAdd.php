<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\KegiatanPengabdianMasyarakat */

$this->title = 'Tambah Kegiatan Pengabdian Masyarakat' . $model->kegiatan_pengabdian_masyarakat_id;
$this->params['breadcrumbs'][] = ['label' => 'Form Rencana Kerja', 'url' => ['dosen/frk']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kegiatan-pengabdian-masyarakat-add">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
        'dataDosen' => $dataDosen,
        'semester' => $semester,
        'dosen'=>$dosen,
    ]) ?>

</div>
