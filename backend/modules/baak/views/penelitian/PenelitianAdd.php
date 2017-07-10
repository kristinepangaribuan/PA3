<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\Penelitian */

$this->title = 'Tambah Penelitian atau Karya Seni dan Teknologi';
$this->params['breadcrumbs'][] = ['label' => 'Form Rencana Kerja', 'url' => ['dosen/frk']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="penelitian-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
        'dataDosen'=>$dataDosen,
        'semester' => $semester,
        'jenisPenelitian' => $jenisPenelitian,
        'tahapanPenelitian' => $tahapanPenelitian,
        'dosen' => $dosen,
    ]) ?>

</div>
