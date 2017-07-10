<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\ModulBahanAjar */

$this->title = 'Tambah Modul Bahan Ajar';
$this->params['breadcrumbs'][] = ['label' => 'Form Rencana Kerja', 'url' => ['dosen/frk']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modul-bahan-ajar-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
//        'modelModulBahanAjar' => $modelModulBahanAjar,
//        'modelsDosenModul' => $modelsDosenModul
        'model' => $model,
        'dataDosen'=>$dataDosen,
        'semester' => $semester,
        'dosen' => $dosen,
        'tahapanModul'=> $tahapanModul,
    ]) ?>

</div>
