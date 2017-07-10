<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\AsistenTugasPraktikum */

$this->title = 'Perbaharui Asisten Tugas Praktikum';
$this->params['breadcrumbs'][] = ['label' => 'Form Rencana Kerja', 'url' => ['dosen/frk']];
$this->params['breadcrumbs'][] = ['label' => $model->kuliah->nama_kul_ind, 'url' => ['asisten-tugas-praktikum-view', 'id' => $model->asisten_tugas_praktikum_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asisten-tugas-praktikum-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'dosen'=>$dosen,
        'model' => $model,
        'dataKuliah' =>$dataKuliah,
        'dataKelas' => $dataKelas,
        'dataDosen' => $dataDosen,
        'resultDosen' => $resultDosen,
        'resultKelas' => $resultKelas,
        'semester' => $semester,
        'prodi'=>$prodi,
    ]) ?>

</div>
