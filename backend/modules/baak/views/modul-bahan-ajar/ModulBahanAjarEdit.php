<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\ModulBahanAjar */

$this->title = 'Perbaharui Modul Bahan Ajar';
$this->params['breadcrumbs'][] = ['label' => 'Form Rencana Kerja', 'url' => ['dosen/frk']];
$this->params['breadcrumbs'][] = ['label' => $model->nama_modul, 'url' => ['modul-bahan-ajar-view', 'id' => $model->modul_bahan_ajar_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modul-bahan-ajar-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
        'dataDosen'=>$dataDosen,
        'result' =>$result,
        'semester' => $semester,
        'dosen' => $dosen,
        'tahapanModul' => $tahapanModul,
    ]) ?>

</div>
