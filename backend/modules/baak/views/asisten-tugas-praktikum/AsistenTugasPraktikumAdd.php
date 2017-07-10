<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\AsistenTugasPraktikum */

$this->title = 'Tambah Asistensi Tugas Praktikum';
$this->params['breadcrumbs'][] = ['label' => 'Form Rencana Kerja', 'url' => ['dosen/frk']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asisten-tugas-praktikum-create">
    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'dosen'=>$dosen,
        'model' => $model,
        'dataKuliah' =>$dataKuliah,
        'dataKelas' => $dataKelas,
        'dataDosen' => $dataDosen,
        'semester' => $semester,
        'prodi'=>$prodi,
    ]) ?>
</div>
